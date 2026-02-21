<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $hotel_id       = $_POST['hotel_id'] ?? '';
    $room_type      = $_POST['room_type'] ?? '';
    $price          = $_POST['price'] ?? '';
    $total_rooms    = $_POST['total_rooms'] ?? '';
    $available      = $_POST['available_rooms'] ?? '';
    $description    = $_POST['description'] ?? '';

    if ($hotel_id == "" || $room_type == "" || $price == "") {
        echo json_encode([
            "status" => false,
            "message" => "Missing fields"
        ]);
        exit;
    }

    $sql = "INSERT INTO room_category
            (hotel_id, room_type, price, total_rooms, available_rooms, description)
            VALUES
            ('$hotel_id', '$room_type', '$price', '$total_rooms', '$available', '$description')";

    if (mysqli_query($con, $sql)) {
        echo json_encode([
            "status" => true,
            "message" => "Room category added successfully"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Failed",
            "error" => mysqli_error($con)
        ]);
    }
}
?>
