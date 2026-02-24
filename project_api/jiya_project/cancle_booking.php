<?php
header("Content-Type: application/json");
include "connect.php";

$data = json_decode(file_get_contents("php://input"), true);

try {

    if (!isset($data['booking_id']) || !isset($data['user_id'])) {
        throw new Exception("Booking ID and User ID required");
    }

    $booking_id = (int)$data['booking_id'];
    $user_id    = (int)$data['user_id'];

    $con->begin_transaction();

    /* ==========================
        CHECK BOOKING
    ========================== */
    $stmtCheck = $con->prepare("
        SELECT booking_status, payment_status
        FROM bookings
        WHERE id = ? AND user_id = ?
    ");
    $stmtCheck->bind_param("ii", $booking_id, $user_id);
    $stmtCheck->execute();
    $res = $stmtCheck->get_result();
    $booking = $res->fetch_assoc();

    if (!$booking) {
        throw new Exception("Booking not found");
    }

    if ($booking['booking_status'] == 2) {
        throw new Exception("Booking already cancelled");
    }

    if (!in_array($booking['booking_status'], [0, 1])) {
        throw new Exception("Booking cannot be cancelled now");
    }

    /* ==========================
        UPDATE BOOKING STATUS
    ========================== */
    $stmtUpdate = $con->prepare("
        UPDATE bookings
        SET booking_status = 2
        WHERE id = ?
    ");
    $stmtUpdate->bind_param("i", $booking_id);
    $stmtUpdate->execute();

    /* ==========================
        BOOKING HISTORY
    ========================== */
    $status = 2; // Cancelled
    $stmtHistory = $con->prepare("
        INSERT INTO booking_history (booking_id, status, changed_at)
        VALUES (?, ?, NOW())
    ");
    $stmtHistory->bind_param("ii", $booking_id, $status);
    $stmtHistory->execute();

    /* ==========================
        COMMIT
    ========================== */
    $con->commit();

    echo json_encode([
        "status" => true,
        "message" => "Booking cancelled successfully"
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