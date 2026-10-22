<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $sql = "SELECT * FROM hotels";
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
            "message" => "No hotels found",
            "data" => []
        ]);
    }
}
?>
