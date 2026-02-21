<?php
include('connect.php');

header('Content-Type: application/json');

$sql = "SELECT id, service_name FROM services ORDER BY id DESC";
$result = $con->query($sql);

$services = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }

    echo json_encode([
        "status" => true,
        "data" => $services
    ]);
} else {
    echo json_encode([
        "status" => false,
        "data" => [],
        "message" => "No services found"
    ]);
}

$con->close();
?>
