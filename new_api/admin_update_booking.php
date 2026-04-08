<?php
header("Content-Type: application/json");
include "connect.php";

$data = json_decode(file_get_contents("php://input"), true);

try {

    if (
        empty($data['payment_id']) ||
        !isset($data['payment_status'])
    ) {
        throw new Exception("Invalid input");
    }

    $payment_id     = (int)$data['payment_id'];
    $payment_status = (int)$data['payment_status'];

    if (!in_array($payment_status, [0, 1])) {
        throw new Exception("Invalid payment status");
    }

    $con->begin_transaction();

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

    $stmtPay = $con->prepare("
        UPDATE payments
        SET payment_status = ?
        WHERE id = ?
    ");
    $stmtPay->bind_param("ii", $payment_status, $payment_id);
    $stmtPay->execute();

    if ($payment_status === 1) {

        $stmtBooking = $con->prepare("
            UPDATE bookings
            SET payment_status = 1, booking_status = 1
            WHERE id = ?
        ");
        $stmtBooking->bind_param("i", $booking_id);
        $stmtBooking->execute();

        $status_code = 1;

    } else {

        $stmtBooking = $con->prepare("
            UPDATE bookings
            SET payment_status = 0
            WHERE id = ?
        ");
        $stmtBooking->bind_param("i", $booking_id);
        $stmtBooking->execute();

        $status_code = 0;
    }

    $stmtHistory = $con->prepare("
        INSERT INTO booking_history (booking_id, status, changed_at)
        VALUES (?, ?, NOW())
    ");
    $stmtHistory->bind_param("ii", $booking_id, $status_code);
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
?>