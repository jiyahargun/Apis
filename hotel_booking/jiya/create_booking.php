<?php
header("Content-Type: application/json");
include "connect.php";

$data = json_decode(file_get_contents("php://input"), true);

try {

    if (
        !isset($data['user_id']) ||
        !isset($data['hotel_id']) ||
        !isset($data['check_in']) ||
        !isset($data['check_out']) ||
        !isset($data['rooms']) ||
        !is_array($data['rooms']) ||
        count($data['rooms']) === 0
    ) {
        throw new Exception("Invalid input data");
    }

    $user_id   = (int)$data['user_id'];
    $hotel_id  = (int)$data['hotel_id'];
    $check_in  = $data['check_in'];
    $check_out = $data['check_out'];
    $rooms     = $data['rooms'];

    $in  = new DateTime($check_in);
    $out = new DateTime($check_out);
    $total_nights = $in->diff($out)->days;

    if ($total_nights <= 0) {
        throw new Exception("Invalid date selection");
    }

    $con->begin_transaction();

    $stmtBooking = $con->prepare("
        INSERT INTO bookings
        (user_id, hotel_id, check_in, check_out, total_price, booking_status, payment_status, created_at)
        VALUES (?, ?, ?, ?, 0, 0, 0, NOW())
    ");
    $stmtBooking->bind_param("iiss", $user_id, $hotel_id, $check_in, $check_out);
    $stmtBooking->execute();

    $booking_id = $stmtBooking->insert_id;

    if ($booking_id <= 0) {
        throw new Exception("Booking creation failed");
    }

    $stmtPrice = $con->prepare("
        SELECT rc.price
        FROM rooms r
        JOIN room_category rc ON rc.id = r.room_category_id
        WHERE r.id = ?
    ");

    $stmtRoom = $con->prepare("
        INSERT INTO booking_rooms
        (booking_id, room_id, price_per_night, total_nights, quantity, room_total, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");

    $grand_total = 0;

    foreach ($rooms as $room) {

        if (!isset($room['room_id']) || !isset($room['quantity'])) {
            throw new Exception("Invalid room data");
        }

        $room_id = (int)$room['room_id'];
        $qty     = (int)$room['quantity'];

        if ($qty <= 0) {
            throw new Exception("Invalid quantity");
        }

        $stmtPrice->bind_param("i", $room_id);
        $stmtPrice->execute();
        $res = $stmtPrice->get_result();
        $row = $res->fetch_assoc();

        if (!$row || $row['price'] === null) {
            throw new Exception("Room price not found for room_id: " . $room_id);
        }

        $price = (float)$row['price'];

        $room_total = $price * $total_nights * $qty;
        $grand_total += $room_total;

        $stmtRoom->bind_param(
            "iidiid",
            $booking_id,
            $room_id,
            $price,
            $total_nights,
            $qty,
            $room_total
        );
        $stmtRoom->execute();
    }

    $stmtUpdate = $con->prepare("
        UPDATE bookings SET total_price = ? WHERE id = ?
    ");
    $stmtUpdate->bind_param("di", $grand_total, $booking_id);
    $stmtUpdate->execute();

    $status = 0;

    $stmtHistory = $con->prepare("
        INSERT INTO booking_history (booking_id, status, changed_at)
        VALUES (?, ?, NOW())
    ");
    $stmtHistory->bind_param("ii", $booking_id, $status);
    $stmtHistory->execute();

    $con->commit();

    echo json_encode([
        "status" => true,
        "message" => "Booking created successfully",
        "booking_id" => $booking_id,
        "total_amount" => $grand_total
    ]);

} catch (Exception $e) {

    $con->rollback();

    echo json_encode([
        "status" => false,
        "message" => $e->getMessage()
    ]);
}
?>