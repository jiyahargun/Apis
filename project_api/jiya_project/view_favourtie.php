<?php
include('connect.php');

$sql = "
SELECT 
    favorites.id,
    hotel_users.name AS user_name,
    hotels.hotel_name
FROM favorites
JOIN hotel_users ON favorites.user_id = hotel_users.id
JOIN hotels ON favorites.hotel_id = hotels.id
";

$result = mysqli_query($con, $sql);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
?>