<?php
header("Content-Type: application/json");
include "connect.php";

$data = json_decode(file_get_contents("php://input"), true);

try {

    //  Validation
    if (
        empty($data['payment_id']) ||
        !isset($data['payment_status'])
    ) {
        throw new Exception("Invalid input");
    }

    $payment_id     = (int)$data['payment_id'];
    $payment_status = (int)$data['payment_status']; // 1 = Paid, 0 = Unpaid

    if (!in_array($payment_status, [0, 1])) {
        throw new Exception("Invalid payment status");
    }

    $con->begin_transaction();

    //  Payment se booking_id nikaalo
    $stmt = $con->prepare("
        SELECT booking_id 
        FROM payments 
        WHERE id = ?
    ");
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $payment = $res->fetch_assoc();

    if (!$payment) {
        throw new Exception("Payment not found");
    }

    $booking_id = (int)$payment['booking_id'];

    //  Update payments table
    $stmtPay = $con->prepare("
        UPDATE payments
        SET payment_status = ?, created_at = NOW()
        WHERE id = ?
    ");
    $stmtPay->bind_param("ii", $payment_status, $payment_id);
    $stmtPay->execute();

    //  Sync booking table
    if ($payment_status === 1) {

        // Payment Approved
        $stmtBooking = $con->prepare("
            UPDATE bookings
            SET payment_status = 1, booking_status = 1
            WHERE id = ?
        ");
        $stmtBooking->bind_param("i", $booking_id);
        $stmtBooking->execute();

        $status_text = "Payment Approved";

    } else {

        //  Payment Reverted / Failed
        $stmtBooking = $con->prepare("
            UPDATE bookings
            SET payment_status = 0, booking_status = 0
            WHERE id = ?
        ");
        $stmtBooking->bind_param("i", $booking_id);
        $stmtBooking->execute();

        $status_text = "Payment Reverted by Admin";
    }

    //  Booking history
    $stmtHistory = $con->prepare("
        INSERT INTO booking_history (booking_id, status, changed_at)
        VALUES (?, ?, NOW())
    ");
    $stmtHistory->bind_param("is", $booking_id, $status_text);
    $stmtHistory->execute();

    $con->commit();

    echo json_encode([
        "status" => true,
        "message" => "Payment & booking status updated successfully"
    ]);

} catch (Exception $e) {

    if (isset($con)) {
        $con->rollback();
    }

    echo json_encode([
        "status" => false,
        "message" => $e->getMessage()
    ]);
}