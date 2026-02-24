<?php
header("Content-Type: application/json");
include "connect.php";

try {

    
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;

    $sql = "
        SELECT 
            b.id AS booking_id,
            b.check_in,
            b.check_out,
            b.total_price,
            b.booking_status,
            b.payment_status,
            b.created_at,
            u.name AS user_name,
            h.hotel_name AS hotel_name
        FROM bookings b
        JOIN hotel_users u ON u.id = b.user_id
        JOIN hotels h ON h.id = b.hotel_id
        ORDER BY b.created_at DESC
        LIMIT ?
    ";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $res = $stmt->get_result();

    $bookings = [];

    while ($row = $res->fetch_assoc()) {

        // 🏷 Status text
        $row['booking_status_text'] =
            $row['booking_status'] == 1 ? "Confirmed" :
            ($row['booking_status'] == 0 ? "Pending" : "Cancelled");

        $row['payment_status_text'] =
            $row['payment_status'] == 1 ? "Paid" : "Unpaid";

        $bookings[] = $row;
    }

    echo json_encode([
        "status" => true,
        "data" => $bookings
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => false,
        "message" => $e->getMessage()
    ]);
}