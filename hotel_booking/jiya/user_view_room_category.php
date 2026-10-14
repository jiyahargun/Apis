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

$sql = "
SELECT 
    rc.id AS room_category_id,
    rc.hotel_id,
    h.hotel_name,
    rc.room_type,
    rc.price,
    rc.total_rooms,
    rc.description,

    (
        SELECT COUNT(r.id) 
        FROM rooms r
        WHERE r.hotel_id = rc.hotel_id
        AND r.room_category_id = rc.id
    ) AS available_rooms

FROM room_category rc
JOIN hotels h ON h.id = rc.hotel_id
WHERE rc.hotel_id = '$hotel_id'
AND rc.room_status = 1
ORDER BY rc.price ASC
";

$result = mysqli_query($con, $sql);

if (!$result) {
    echo json_encode([
        "status" => false,
        "message" => mysqli_error($con)
    ]);
    exit;
}

$data = [];

while ($row = mysqli_fetch_assoc($result)) {

    $data[] = [
        "room_category_id" => $row['room_category_id'],
        "hotel_id" => $row['hotel_id'],
        "hotel_name" => $row['hotel_name'],
        "room_type" => $row['room_type'],
        "price" => (float)$row['price'],
        "total_rooms" => (int)$row['total_rooms'],
        "available_rooms" => (int)$row['available_rooms'],
        "description" => $row['description']
    ];
}

echo json_encode([
    "status" => true,
    "data" => $data
]);
?>