<?php
include 'connect.php';

if (isset($_GET['room_category_id'])) {
    $room_category_id = $_GET['room_category_id'];
    $query = mysqli_query($con,
        "SELECT * FROM room_images WHERE room_category_id='$room_category_id'"
    );
} else {
    $query = mysqli_query($con,
        "SELECT * FROM room_images"
    );
}

$data = [];

while($row = mysqli_fetch_assoc($query)){
    $data[] = $row;
}

echo json_encode([
    "status" => true,
    "data" => $data
]);
?>
