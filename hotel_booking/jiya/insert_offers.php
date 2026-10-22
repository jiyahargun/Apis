<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $hotel_id         = $_POST['hotel_id'] ?? '';
    $title            = $_POST['title'] ?? '';
    $description      = $_POST['description'] ?? '';
    $discount_percent    = $_POST['discount_percent'] ?? '';
    $start_date       = $_POST['start_date'] ?? '';
    $end_date         = $_POST['end_date'] ?? '';

    // Validation
    if (
        $hotel_id == "" ||
        $title == "" ||
        $description == "" ||
        $discount_percent == "" ||
        $start_date == "" ||
        $end_date == "" 
    ) {
        echo json_encode([
            "status" => false,
            "message" => "All required fields must be filled"
        ]);
        exit;
    }

    // Date validation
    if ($start_date > $end_date) {
        echo json_encode([
            "status" => false,
            "message" => "Start date cannot be greater than end date"
        ]);
        exit;
    }

    $sql = "INSERT INTO offers
            (hotel_id, title, description, discount_percent, start_date, end_date)
            VALUES
            ('$hotel_id', '$title', '$description', '$discount_percent', '$start_date', '$end_date')";

    if (mysqli_query($con, $sql)) {
        echo json_encode([
            "status" => true,
            "message" => "Offer added successfully"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Insert failed",
            "error" => mysqli_error($con)
        ]);
    }
}
?>
