<?php
include('connect.php');

$id               = $_POST['id'] ?? '';
$hotel_id         = $_POST['hotel_id'] ?? '';
$title            = $_POST['title'] ?? '';
$discount_percent = $_POST['discount_percent'] ?? '';
$start_date       = $_POST['start_date'] ?? '';
$end_date         = $_POST['end_date'] ?? '';

if (
    $id == "" ||
    $hotel_id == "" ||
    $title == "" ||
    $discount_percent == "" ||
    $start_date == "" ||
    $end_date == ""
) {
    echo json_encode([
        "status" => false,
        "message" => "All fields required"
    ]);
    exit;
}

$sql = "UPDATE offers SET
            hotel_id='$hotel_id',
            title='$title',
            discount_percent='$discount_percent',
            start_date='$start_date',
            end_date='$end_date'
        WHERE id='$id'";

if (mysqli_query($con, $sql)) {
    echo json_encode([
        "status" => true,
        "message" => "Offer updated successfully"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Update failed",
        "error" => mysqli_error($con)
    ]);
}
?>
