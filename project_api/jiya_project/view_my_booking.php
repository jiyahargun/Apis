<?php
header("Content-Type: application/json");
include "connect.php";

$data = json_decode(file_get_contents("php://input"), true);

try {

    if (!isset($data['user_id'])) {
        throw new Exception("User ID required");
    }

    $user_id = (int)$data['user_id'];

    /* ==========================
        FETCH BOOKINGS
    ========================== */
    $stmt = $con->prepare("
        SELECT 
            b.id AS booking_id,
            b.hotel_id,
            h.hotel_name AS hotel_name,
            b.check_in,
            b.check_out,
            b.total_price,
            b.booking_status,
            b.payment_status,
            b.created_at
        FROM bookings b
        JOIN hotels h ON h.id = b.hotel_id
        WHERE b.user_id = ?
        ORDER BY b.id DESC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $bookings = [];

    /* ==========================
        LOOP BOOKINGS
    ========================== */
    while ($row = $result->fetch_assoc()) {

        $booking_id = $row['booking_id'];

        // Fetch rooms for each booking
        $stmtRooms = $con->prepare("
            SELECT 
    br.room_id,
    r.room_number,
    rc.room_type AS room_category,
    br.price_per_night,
    br.total_nights,
    br.quantity,
    br.room_total
FROM booking_rooms br
JOIN rooms r ON r.id = br.room_id
JOIN room_category rc ON rc.id = r.room_category_id
WHERE br.booking_id = ?
        ");
        $stmtRooms->bind_param("i", $booking_id);
        $stmtRooms->execute();
        $resRooms = $stmtRooms->get_result();

        $rooms = [];
        while ($room = $resRooms->fetch_assoc()) {
            $rooms[] = $room;
        }

        $row['rooms'] = $rooms;
        $bookings[] = $row;
    }

    echo json_encode([
        "status" => true,
        "bookings" => $bookings
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => false,
        "message" => $e->getMessage()
    ]);
}
?>