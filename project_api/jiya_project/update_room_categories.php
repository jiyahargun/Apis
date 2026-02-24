<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id              = $_POST['id'] ?? '';
    $hotel_id        = $_POST['hotel_id'] ?? '';
    $room_type       = $_POST['room_type'] ?? '';
    $price           = $_POST['price'] ?? '';
    $total_rooms     = $_POST['total_rooms'] ?? '';
    $available_rooms = $_POST['available_rooms'] ?? '';
    $description     = $_POST['description'] ?? '';

    // Validation
    if ($id == "" || $hotel_id == "" || $room_type == "" || $price == "") {
        echo json_encode([
            "status" => false,
            "message" => "Missing required fields"
        ]);
        exit;
    }

    $sql = "UPDATE room_category SET 
                hotel_id='$hotel_id',
                room_type='$room_type',
                price='$price',
                total_rooms='$total_rooms',
                available_rooms='$available_rooms',
                description='$description'
            WHERE id='$id'";

    if (mysqli_query($con, $sql)) {
        echo json_encode([
            "status" => true,
            "message" => "Room category updated successfully"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Update failed",
            "error" => mysqli_error($con)
        ]);
    }
}
?>
