<?php
header("Content-Type: application/json");
include "connect.php";

$data = json_decode(file_get_contents("php://input"), true);

try {

    if (empty($data['booking_id'])) {
        throw new Exception("Booking ID required");
    }

    $booking_id = (int)$data['booking_id'];

    /* ==========================
       FETCH BOOKING HEADER
    ========================== */
    $stmt = $con->prepare("
        SELECT 
            b.id AS booking_id,
            b.user_id,
            b.hotel_id,
            h.hotel_name,
            b.check_in,
            b.check_out,
            b.total_price,
            b.booking_status,
            b.payment_status,
            b.created_at
        FROM bookings b
        JOIN hotels h ON h.id = b.hotel_id
        WHERE b.id = ?
    ");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        throw new Exception("Booking not found");
    }

    $booking = $result->fetch_assoc();

    /* ==========================
       FETCH BOOKING ROOMS
    ========================== */
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
    while ($row = $resRooms->fetch_assoc()) {
        $rooms[] = $row;
    }

    $booking['rooms'] = $rooms;

    echo json_encode([
        "status" => true,
        "booking" => $booking
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => false,
        "message" => $e->getMessage()
    ]);
}
?>