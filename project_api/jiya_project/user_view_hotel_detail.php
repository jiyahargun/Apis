<?php
include('connect.php');
header('Content-Type: application/json');

$hotel_id = $_GET['hotel_id'] ?? '';

if ($hotel_id == "") {
    echo json_encode([
        "status" => false,
        "message" => "hotel_id required"
    ]);
    exit;
}

/* ================= HOTEL BASIC INFO ================= */

$sql = "
SELECT 
    h.id AS hotel_id,
    h.hotel_name,
    h.address,
    h.description,
    h.rating,
    h.payment_enabled,
    c.city_name
FROM hotels h
LEFT JOIN cities c ON c.id = h.city_id
WHERE h.id = '$hotel_id'
AND h.hotel_status = 1
LIMIT 1
";

$result = mysqli_query($con, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    echo json_encode([
        "status" => false,
        "message" => "Hotel not found"
    ]);
    exit;
}

$hotel = mysqli_fetch_assoc($result);

/* ================= HOTEL IMAGES ================= */

$images = [];

$image_query = mysqli_query($con, "
    SELECT image 
    FROM hotel_images 
    WHERE hotel_id = '$hotel_id'
");

while ($img = mysqli_fetch_assoc($image_query)) {
    $images[] = $img['image'];
}

/* ================= ACTIVE OFFER ================= */

$offer = null;

$offer_query = mysqli_query($con, "
    SELECT title, description, discount_percent
    FROM offers 
    WHERE hotel_id = '$hotel_id'
    AND offer_status = 1
    ORDER BY id DESC
    LIMIT 1
");

if ($offer_query && mysqli_num_rows($offer_query) > 0) {
    $offer = mysqli_fetch_assoc($offer_query);
}

/* ================= HOTEL SERVICES (JOIN FIXED) ================= */

$services = [];

$service_query = mysqli_query($con, "
    SELECT s.service_name
    FROM hotel_service hs
    JOIN services s ON s.id = hs.service_id
    WHERE hs.hotel_id = '$hotel_id'
");

while ($row = mysqli_fetch_assoc($service_query)) {
    $services[] = $row['service_name'];
}

/* ================= FINAL RESPONSE ================= */

$response = [
    "status" => true,
    "data" => [
        "hotel_id" => $hotel['hotel_id'],
        "hotel_name" => $hotel['hotel_name'],
        "city_name" => $hotel['city_name'],
        "address" => $hotel['address'],
        "description" => $hotel['description'],
        "rating" => (float)$hotel['rating'],
        "payment_enabled" => (int)$hotel['payment_enabled'],
        "images" => $images,
        "offer" => $offer,
        "services" => $services
    ]
];

echo json_encode($response);
?>