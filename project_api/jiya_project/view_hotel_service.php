<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $sql = "SELECT 
                hotel_service.id,
                hotels.hotel_name,
                services.service_name
            FROM hotel_service
            JOIN hotels ON hotel_service.hotel_id = hotels.id
            JOIN services ON hotel_service.service_id = services.id";

    $result = mysqli_query($con, $sql);

    if (!$result) {
        echo json_encode([
            "status" => false,
            "message" => "Query failed",
            "error" => mysqli_error($con)
        ]);
        exit;
    }

    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    if (count($data) > 0) {
        echo json_encode([
            "status" => true,
            "data" => $data
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "No services assigned to hotels",
            "data" => []
        ]);
    }
}
?>
