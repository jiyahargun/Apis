<?php
include('connect.php');

$sql = "SELECT 
            offers.id,
            hotels.hotel_name,
            offers.title,
            offers.discount_percent,
            offers.start_date,
            offers.end_date
        FROM offers
        JOIN hotels ON offers.hotel_id = hotels.id";

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
