<?php
header("Content-Type: application/json");
include "connect.php";

$data = json_decode(file_get_contents("php://input"), true);

try {

    if (!isset($data['booking_id'])) {
        throw new Exception("Booking ID required");
    }

    $booking_id = (int)$data['booking_id'];

    /* ==========================
        FETCH BOOKING BASIC INFO
    ========================== */
    $stmtBooking = $con->prepare("
        SELECT 
            b.id AS booking_id,
            b.booking_status,
            b.payment_status,
            b.created_at,
            h.hotel_name AS hotel_name
        FROM bookings b
        JOIN hotels h ON h.id = b.hotel_id
        WHERE b.id = ?
    ");
    $stmtBooking->bind_param("i", $booking_id);
    $stmtBooking->execute();
    $booking = $stmtBooking->get_result()->fetch_assoc();

    if (!$booking) {
        throw new Exception("Booking not found");
    }

    /* ==========================
        FETCH HISTORY
    ========================== */
    $stmtHistory = $con->prepare("
        SELECT 
            status,
            changed_at
        FROM booking_history
        WHERE booking_id = ?
        ORDER BY changed_at ASC
    ");
    $stmtHistory->bind_param("i", $booking_id);
    $stmtHistory->execute();
    $resHistory = $stmtHistory->get_result();

    $history = [];
    while ($row = $resHistory->fetch_assoc()) {
        $history[] = [
            "status_code" => (int)$row['status'],
            "status_text" => getStatusText($row['status']),
            "changed_at"  => $row['changed_at']
        ];
    }

    echo json_encode([
        "status" => true,
        "booking" => $booking,
        "history" => $history
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => false,
        "message" => $e->getMessage()
    ]);
}

/* ==========================
   STATUS TEXT HELPER
========================== */
function getStatusText($status) {
    switch ((int)$status) {
        case 0: return "Pending";
        case 1: return "Confirmed";
        case 2: return "Cancelled";
        case 3: return "Checked-in";
        case 4: return "Checked-out";
        default: return "Unknown";
    }
}
?>