<?php
include('connect.php');

$user_id  = $_POST['user_id'] ?? '';
$hotel_id = $_POST['hotel_id'] ?? '';

if ($user_id == "" || $hotel_id == "") {
    echo json_encode([
        "status" => false,
        "message" => "User id and hotel id required"
    ]);
    exit;
}

/* Duplicate check */
$check = $con->prepare(
    "SELECT id FROM favorites WHERE user_id = ? AND hotel_id = ?"
);
$check->bind_param("ii", $user_id, $hotel_id);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    echo json_encode([
        "status" => false,
        "message" => "Hotel already in favourites"
    ]);
    exit;
}

/* Insert */
$stmt = $con->prepare(
    "INSERT INTO favorites (user_id, hotel_id) VALUES (?, ?)"
);
$stmt->bind_param("ii", $user_id, $hotel_id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "message" => "Favourite added successfully"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Failed to add favourite"
    ]);
}
?>
