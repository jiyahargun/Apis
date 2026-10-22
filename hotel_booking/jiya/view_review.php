<?php
include('connect.php');

$sql = "SELECT 
    reviews.id,
    hotel_users.name AS user_name,
    hotels.hotel_name,
    reviews.rating,
    reviews.review,
    reviews.review_status
FROM reviews
JOIN hotel_users ON reviews.user_id = hotel_users.id
JOIN hotels ON reviews.hotel_id = hotels.id";

$result = mysqli_query($con, $sql);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode([
    "code" => 200,
    "data" => $data
]);
?>
