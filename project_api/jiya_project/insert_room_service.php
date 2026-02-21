<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $room_id   = $_POST['room_id'] ?? '';
    $service_id = $_POST['service_id'] ?? '';

    if ($room_id == "" || $service_id == "") {
        echo json_encode([
            "status" => false,
            "message" => "Room category id and service id required"
        ]);
        exit;
    }

    // Check duplicate
    $check = "SELECT id FROM rooms_services 
              WHERE room_id='$room_id' AND service_id='$service_id'";
    $res = mysqli_query($con, $check);

    if (mysqli_num_rows($res) > 0) {
        echo json_encode([
            "status" => false,
            "message" => "Service already assigned to this room category"
        ]);
        exit;
    }

    $sql = "INSERT INTO rooms_services (room_id, service_id)
            VALUES ('$room_id', '$service_id')";

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
