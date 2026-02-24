<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $sql = "SELECT 
                rooms_services.id,
                room_category.room_type,
                services.service_name
            FROM rooms_services
            JOIN room_category 
                ON rooms_services.room_id = room_category.id
            JOIN services 
                ON rooms_services.service_id = services.id";

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
            "message" => "No room services found",
            "data" => []
        ]);
    }
}
?>
