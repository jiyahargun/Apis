<?php
include('connect.php');

$city_id = $_POST['city_id'] ?? '';
$hotel_name = $_POST['hotel_name'] ?? '';
$address = $_POST['address'] ?? '';
$description = $_POST['description'] ?? '';
$rating = $_POST['rating'] ?? '';
$payment_enabled = $_POST['payment_enabled'] ?? 1;
$hotel_status = $_POST['hotel_status'] ?? null;

// Validation
if ($city_id == '' || $hotel_name == '') {
    echo json_encode([
        "code" => "error",
        "message" => "City ID & Hotel Name required"
    ]);
    exit;
}

// Insert Query
$stmt = $con->prepare(
    "INSERT INTO hotels 
    (city_id, hotel_name, address, description, rating, payment_enabled, hotel_status)
    VALUES (?, ?, ?, ?, ?, ?, ?)"
);


$stmt->bind_param(
    "isssdii",
    $city_id,
    $hotel_name,
    $address,
    $description,
    $rating,
    $payment_enabled,
    $hotel_status
);

if ($stmt->execute()) {
    echo json_encode([
        "code" => "success",
        "message" => "Hotel Added Successfully"
    ]);
} else {
    echo json_encode([
        "code" => "Error",
        "message" => "Failed to add hotel"
    ]);
}

$stmt->close();
$con->close();
?>
