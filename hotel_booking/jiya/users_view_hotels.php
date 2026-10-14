<?php
include('connect.php');
header('Content-Type: application/json');

$sql = "
SELECT 
    h.id AS hotel_id,
    h.hotel_name,
    c.city_name,
    h.rating,
    h.payment_enabled,

    -- Starting Price
    COALESCE(
        (SELECT MIN(rc.price) 
         FROM room_category rc 
         WHERE rc.hotel_id = h.id
        ), 0
    ) AS starting_price,

    -- Primary Image
    (SELECT hi.image 
     FROM hotel_images hi 
     WHERE hi.hotel_id = h.id 
     LIMIT 1
    ) AS hotel_image,

    -- Active Offer (No date check)
    COALESCE(
        (SELECT o.discount_percent 
         FROM offers o 
         WHERE o.hotel_id = h.id 
         AND o.offer_status = 1
         ORDER BY o.id DESC
         LIMIT 1
        ), 0
    ) AS discount_percent

FROM hotels h
LEFT JOIN cities c ON c.id = h.city_id
WHERE h.hotel_status = 1
";

$result = mysqli_query($con, $sql);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {

    $discount = $row['discount_percent'];

    $data[] = [
        "hotel_id" => $row['hotel_id'],
        "hotel_name" => $row['hotel_name'],
        "city_name" => $row['city_name'],
        "hotel_image" => $row['hotel_image'],
        "rating" => $row['rating'],
        "starting_price" => $row['starting_price'],
        "discount_percent" => $discount,
        "discount_label" => $discount > 0 ? $discount . "% OFF" : "",
        "payment_enabled" => $row['payment_enabled']
    ];
}

echo json_encode([
    "status" => true,
    "data" => $data
]);
?>