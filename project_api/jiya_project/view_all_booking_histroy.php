<?php
header("Content-Type: application/json");
include "connect.php";

try {

    /* ==========================
        FETCH ALL HISTORY
    ========================== */
    $stmt = $con->prepare("
        SELECT 
            bh.id AS history_id,
            bh.booking_id,
            bh.status,
            bh.changed_at,

            b.user_id,
            u.name AS user_name,

            b.hotel_id,
            h.hotel_name AS hotel_name
        FROM booking_history bh
        JOIN bookings b ON b.id = bh.booking_id
        JOIN users u ON u.id = b.user_id
        JOIN hotels h ON h.id = b.hotel_id
        ORDER BY bh.changed_at DESC
    ");
    $stmt->execute();
    $result = $stmt->get_result();

    $history = [];

    while ($row = $result->fetch_assoc()) {
        $history[] = [
            "history_id"   => (int)$row['history_id'],
            "booking_id"   => (int)$row['booking_id'],
            "user_id"      => (int)$row['user_id'],
            "user_name"    => $row['user_name'],
            "hotel_id"     => (int)$row['hotel_id'],
            "hotel_name"   => $row['hotel_name'],
            "status_code"  => (int)$row['status'],
            "status_text"  => getStatusText($row['status']),
            "changed_at"   => $row['changed_at']
        ];
    }

    echo json_encode([
        "status" => true,
        "total_records" => count($history),
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