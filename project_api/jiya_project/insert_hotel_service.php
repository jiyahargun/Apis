<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $hotel_id   = $_POST['hotel_id'] ?? '';
    $service_id = $_POST['service_id'] ?? '';

    if ($hotel_id == "" || $service_id == "") {
        echo json_encode([
            "status" => false,
            "message" => "Hotel id and service id required"
        ]);
        exit;
    }

    // Check duplicate
    $check = "SELECT id FROM hotel_service 
              WHERE hotel_id='$hotel_id' AND service_id='$service_id'";
    $res = mysqli_query($con, $check);

    if (mysqli_num_rows($res) > 0) {
        echo json_encode([
            "status" => false,
            "message" => "Service already assigned to this hotel"
        ]);
        exit;
    }

    $sql = "INSERT INTO hotel_service (hotel_id, service_id)
            VALUES ('$hotel_id', '$service_id')";

    if (mysqli_query($con, $sql)) {
        echo json_encode([
            "status" => true,
            "message" => "Service assigned successfully"
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
