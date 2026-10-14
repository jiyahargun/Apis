<?php
include('connect.php');

$data = json_decode(file_get_contents("php://input"), true);

$hotel_id  = $data['hotel_id'] ?? '';
$check_in  = $data['check_in'] ?? '';
$check_out = $data['check_out'] ?? '';

if ($hotel_id == "" || $check_in == "" || $check_out == "") {
    echo json_encode([
        "code" => 400,
        "message" => "All fields are required"
    ]);
    exit;
}

/*
Assume:
booking_status = 1 → confirmed
booking_status = 2 → cancelled
*/

$sql = "SELECT id FROM bookings 
        WHERE hotel_id = '$hotel_id'
        AND booking_status != 2
        AND (
            ('$check_in' < check_out) 
            AND 
            ('$check_out' > check_in)
        )";

$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) {
    echo json_encode([
        "code" => 200,
        "available" => false,
        "message" => "Hotel not available for selected dates"
    ]);
} else {
    echo json_encode([
        "code" => 200,
        "available" => true,
        "message" => "Hotel available"
    ]);
}
?>