<?php
header("Content-Type: application/json");
include "connect.php";

try {

    $sql = "
        SELECT 
            p.id AS payment_id,
            p.amount,
            p.payment_method,
            p.payment_status,
            p.transaction_id,
            p.created_at,
            u.name AS user_name,
            h.hotel_name AS hotel_name,
            b.id AS booking_id
        FROM payments p
        JOIN users u ON u.id = p.user_id
        JOIN bookings b ON b.id = p.booking_id
        JOIN hotels h ON h.id = b.hotel_id
        ORDER BY p.created_at DESC
    ";

    $res = $con->query($sql);

    $payments = [];

    while ($row = $res->fetch_assoc()) {
        $row['payment_status_text'] =
            $row['payment_status'] == 1 ? "Paid" : "Pending";
        $payments[] = $row;
    }

    echo json_encode([
        "status" => true,
        "data" => $payments
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => false,
        "message" => $e->getMessage()
    ]);
}