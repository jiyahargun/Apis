<?php
include 'connect.php';

if (isset($_GET['hotel_id'])) {
    $hotel_id = $_GET['hotel_id'];
    $query = mysqli_query($con,
        "SELECT * FROM hotel_images WHERE hotel_id='$hotel_id'"
    );
} else {
    $query = mysqli_query($con,
        "SELECT * FROM hotel_images"
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
