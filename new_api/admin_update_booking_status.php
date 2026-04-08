<?php
header("Content-Type: application/json");
include "connect.php";

try {

    $booking_id = 0;
    $new_status = 0;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
        $booking_id = isset($data['booking_id']) ? (int)$data['booking_id'] : 0;
        $new_status = isset($data['new_status']) ? (int)$data['new_status'] : 0;
    } else {
        $booking_id = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;
        $new_status = isset($_GET['new_status']) ? (int)$_GET['new_status'] : 0;
    }

    if ($booking_id <= 0) {
        throw new Exception("Booking ID required");
    }

    $allowed_status = [0, 1, 2, 3, 4];
    if (!in_array($new_status, $allowed_status)) {
        throw new Exception("Invalid booking status");
    }

    $con->begin_transaction();

    $stmtCheck = $con->prepare("
        SELECT booking_status
        FROM bookings
        WHERE id = ?
    ");
    $stmtCheck->bind_param("i", $booking_id);
    $stmtCheck->execute();
    $res = $stmtCheck->get_result();
    $booking = $res->fetch_assoc();

    if (!$booking) {
        throw new Exception("Booking not found");
    }

    $current_status = (int)$booking['booking_status'];

    if ($current_status === $new_status) {
        throw new Exception("Booking already in this status");
    }

    if ($current_status === 2) {
        throw new Exception("Cancelled booking cannot be updated");
    }

    if ($current_status === 4) {
        throw new Exception("Checked-out booking cannot be updated");
    }

    $stmtUpdate = $con->prepare("
        UPDATE bookings
        SET booking_status = ?
        WHERE id = ?
    ");
    $stmtUpdate->bind_param("ii", $new_status, $booking_id);
    $stmtUpdate->execute();

    $stmtHistory = $con->prepare("
        INSERT INTO booking_history (booking_id, status, changed_at)
        VALUES (?, ?, NOW())
    ");
    $stmtHistory->bind_param("ii", $booking_id, $new_status);
    $stmtHistory->execute();

    $con->commit();

    echo json_encode([
        "status" => true,
        "message" => "Booking status updated successfully"
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