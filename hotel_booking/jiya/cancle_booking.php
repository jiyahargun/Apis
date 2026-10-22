<?php
header("Content-Type: application/json");
include "connect.php";

try {

    $booking_id = 0;
    $user_id = 0;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
        $booking_id = isset($data['booking_id']) ? (int)$data['booking_id'] : 0;
        $user_id    = isset($data['user_id']) ? (int)$data['user_id'] : 0;
    } else {
        $booking_id = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;
        $user_id    = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
    }

    if ($booking_id <= 0 || $user_id <= 0) {
        throw new Exception("Booking ID and User ID required");
    }

    $con->begin_transaction();

    $stmtCheck = $con->prepare("
        SELECT booking_status
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

    $stmtUpdate = $con->prepare("
        UPDATE bookings
        SET booking_status = 2
        WHERE id = ?
    ");
    $stmtUpdate->bind_param("i", $booking_id);
    $stmtUpdate->execute();

    $status = 2;

    $stmtHistory = $con->prepare("
        INSERT INTO booking_history (booking_id, status, changed_at)
        VALUES (?, ?, NOW())
    ");
    $stmtHistory->bind_param("ii", $booking_id, $status);
    $stmtHistory->execute();

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