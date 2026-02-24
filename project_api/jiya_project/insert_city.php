<?php
include('connect.php');

$name = $_POST['city_name'] ?? '';

if ($name == "") {
    echo json_encode(["message" => "City name required"]);
    exit;
}

$sql = "INSERT INTO cities (city_name) VALUES ('$name')";

if (mysqli_query($con, $sql)) {
    echo json_encode(["message" => "City added successfully"]);
} else {
    echo json_encode(["message" => "Error adding city"]);
}
exit;
?>
