<?php
header("Content-Type: application/json");
include "connect.php";

try {

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
    } else {
        $data = json_decode(file_get_contents("php://input"), true);
        $user_id = isset($data['user_id']) ? (int)$data['user_id'] : 0;
    }

    if ($user_id <= 0) {
        throw new Exception("User ID required");
    }

    $sql = "
        SELECT 
            p.id AS payment_id,
            p.amount,
            p.payment_method,
            p.payment_status,
            p.created_at,
            b.id AS booking_id,
            h.hotel_name AS hotel_name
        FROM payments p
        JOIN bookings b ON b.id = p.booking_id
        JOIN hotels h ON h.id = b.hotel_id
        WHERE p.user_id = ?
        ORDER BY p.created_at DESC
    ";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();

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