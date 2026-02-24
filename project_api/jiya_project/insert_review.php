<?php
include('connect.php');

$user_id = $_POST['user_id'] ?? '';
$hotel_id = $_POST['hotel_id'] ?? '';
$rating = $_POST['rating'] ?? '';
$review = $_POST['review'] ?? '';
$review_status = $_POST['review_status'] ?? 1;

// Validation
if ($user_id == '' || $hotel_id == '' || $rating == '') {
    echo json_encode([
        "code" => 400,
        "message" => "User, Hotel & Rating required"
    ]);
    exit;
}

if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    echo json_encode([
        "code" => 400,
        "message" => "Rating must be between 1 and 5"
    ]);
    exit;
}

// Insert (Prepared Statement)
$stmt = $con->prepare(
    "INSERT INTO reviews (user_id, hotel_id, rating, review, review_status)
     VALUES (?, ?, ?, ?, ?)"
);

$stmt->bind_param("iiisi", $user_id, $hotel_id, $rating, $review, $review_status);

if ($stmt->execute()) {
    echo json_encode([
        "code" => 200,
        "message" => "Review Added Successfully"
    ]);
} else {
    echo json_encode([
        "code" => 500,
        "message" => "Failed to add review"
    ]);
}

$stmt->close();
$con->close();
?>
