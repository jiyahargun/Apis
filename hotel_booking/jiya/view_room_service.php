<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $sql = "SELECT 
                rs.id,
                r.id AS room_id,
                rc.room_type,
                s.id AS service_id,
                s.service_name
            FROM rooms_services rs
            JOIN rooms r 
                ON rs.room_id = r.id
            JOIN room_category rc 
                ON r.room_category_id = rc.id
            JOIN services s 
                ON rs.service_id = s.id
            ORDER BY rs.id DESC";

    $result = mysqli_query($con, $sql);

    if (!$result) {
        echo json_encode([
            "status" => false,
            "message" => "Query failed",
            "error" => mysqli_error($con),
            "data" => []
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
            "message" => "Data fetched successfully",
            "data" => $data
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "No data found",
            "data" => []
        ]);
    }
}
?>