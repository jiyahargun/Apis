<?php
header("Content-Type: application/json");
include "connect.php";

$data = json_decode(file_get_contents("php://input"), true);

try {

    if (
        empty($data['booking_id']) ||
        empty($data['user_id']) ||
        empty($data['amount']) ||
        empty($data['payment_method'])
    ) {
        throw new Exception("Invalid input data");
    }

    $booking_id      = (int)$data['booking_id'];
    $user_id         = (int)$data['user_id'];
    $amount          = (float)$data['amount'];
    $payment_method  = $data['payment_method'];
    $transaction_id  = $data['transaction_id'] ?? null;

    $con->begin_transaction();

    /* ==========================
       1️⃣ INSERT PAYMENT
    ========================== */
    $stmt = $con->prepare("
        INSERT INTO payments
        (booking_id, user_id, amount, payment_method, transaction_id, payment_status, created_at)
        VALUES (?, ?, ?, ?, ?, 1, NOW())
    ");
    $stmt->bind_param(
        "iidss",
        $booking_id,
        $user_id,
        $amount,
        $payment_method,
        $transaction_id
    );
    $stmt->execute();

    /* ==========================
       2️⃣ UPDATE BOOKING
    ========================== */
    $stmtBooking = $con->prepare("
        UPDATE bookings
        SET payment_status = 1,
            booking_status = 1
        WHERE id = ?
    ");
    $stmtBooking->bind_param("i", $booking_id);
    $stmtBooking->execute();

    /* ==========================
       3️⃣ BOOKING HISTORY
    ========================== */
    $status_confirmed = 1;
    $stmtHistory = $con->prepare("
        INSERT INTO booking_history (booking_id, status, changed_at)
        VALUES (?, ?, NOW())
    ");
    $stmtHistory->bind_param("ii", $booking_id, $status_confirmed);
    $stmtHistory->execute();

    $con->commit();

    echo json_encode([
        "status" => true,
        "message" => "Payment successful & booking confirmed"
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