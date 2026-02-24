<?php
header("Content-Type: application/json");
include "connect.php";

$data = json_decode(file_get_contents("php://input"), true);

try {

    if (empty($data['user_id'])) {
        throw new Exception("User id required");
    }

    $user_id = (int)$data['user_id'];

    $stmt = $con->prepare("
        SELECT
            p.id AS payment_id,
            p.booking_id,
            h.hotel_name AS hotel_name,
            p.amount,
            p.payment_method,
            p.transaction_id,
            p.payment_status,
            p.created_at
        FROM payments p
        JOIN bookings b ON b.id = p.booking_id
        JOIN hotels h ON h.id = b.hotel_id
        WHERE p.user_id = ?
        ORDER BY p.created_at DESC
    ");

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $payments = [];

    while ($row = $result->fetch_assoc()) {

        $row['payment_status_text'] =
            $row['payment_status'] == 1 ? "Success" : "Failed";

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
?>