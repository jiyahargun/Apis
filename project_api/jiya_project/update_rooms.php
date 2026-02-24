<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST['id'] ?? '';
    $hotel_id = $_POST['hotel_id'] ?? '';
    $room_category_id = $_POST['room_category_id'] ?? '';
    $room_number = $_POST['room_number'] ?? '';

    if ($id == "" || $hotel_id == "" || $room_category_id == "" || $room_number == "") {
        echo json_encode([
            "status" => false,
            "message" => "Missing fields"
        ]);
        exit;
    }

    $sql = "UPDATE rooms SET
                hotel_id = '$hotel_id',
                room_category_id = '$room_category_id',
                room_number = '$room_number'
            WHERE id = '$id'";

    if (mysqli_query($con, $sql)) {
        echo json_encode([
            "status" => true,
            "message" => "Room updated successfully"
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
