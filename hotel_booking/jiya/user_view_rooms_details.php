<?php
include('connect.php');
header('Content-Type: application/json');

$hotel_id = $_GET['hotel_id'] ?? '';
$room_category_id = $_GET['room_category_id'] ?? '';

if ($hotel_id == "" || $room_category_id == "") {
    echo json_encode([
        "status" => false,
        "message" => "hotel_id and room_category_id required"
    ]);
    exit;
}

/* ================= CATEGORY + HOTEL INFO ================= */

$sql = "
SELECT 
    rc.id AS room_category_id,
    rc.hotel_id,
    h.hotel_name,
    rc.room_type,
    rc.price,
    rc.description,
    rc.total_rooms,

    (
        SELECT COUNT(r.id)
        FROM rooms r
        WHERE r.hotel_id = rc.hotel_id
        AND r.room_category_id = rc.id
    ) AS available_rooms

FROM room_category rc
JOIN hotels h ON h.id = rc.hotel_id
WHERE rc.id = '$room_category_id'
AND rc.hotel_id = '$hotel_id'
AND rc.room_status = 1
LIMIT 1
";

$result = mysqli_query($con, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    echo json_encode([
        "status" => false,
        "message" => "Room category not found"
    ]);
    exit;
}

$category = mysqli_fetch_assoc($result);

/* ================= ROOM SERVICES ================= */

$services = [];

$service_query = mysqli_query($con, "
    SELECT DISTINCT s.service_name
    FROM rooms_services rs
    JOIN services s ON s.id = rs.service_id
    JOIN rooms r ON r.id = rs.room_id
    WHERE r.room_category_id = '$room_category_id'
    LIMIT 10
");

while ($row = mysqli_fetch_assoc($service_query)) {
    $services[] = $row['service_name'];
}

/* ================= FINAL RESPONSE ================= */

$response = [
    "status" => true,
    "data" => [
        "room_category_id" => $category['room_category_id'],
        "hotel_id" => $category['hotel_id'],
        "hotel_name" => $category['hotel_name'],
        "room_type" => $category['room_type'],
        "price" => (float)$category['price'],
        "description" => $category['description'],
        "total_rooms" => (int)$category['total_rooms'],
        "available_rooms" => (int)$category['available_rooms'],
        "services" => $services
    ]
];

echo json_encode($response);
?>