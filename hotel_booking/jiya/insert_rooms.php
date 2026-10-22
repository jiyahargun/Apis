<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $hotel_id       = $_POST['hotel_id'] ?? '';
    $room_category_id       = $_POST['room_category_id'] ?? '';
    $room_number          = $_POST['room_number'] ?? '';
    

    if ($hotel_id == "" || $room_category_id  == "" || $room_number == "") {
        echo json_encode([
            "status" => false,
            "message" => "Missing fields"
        ]);
        exit;
    }

    $sql = "INSERT INTO rooms
            (hotel_id, room_category_id, room_number)
            VALUES
            ('$hotel_id', '$room_category_id', '$room_number')";

    if (mysqli_query($con, $sql)) {
        echo json_encode([
            "status" => true,
            "message" => "Rooms added successfully"
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
