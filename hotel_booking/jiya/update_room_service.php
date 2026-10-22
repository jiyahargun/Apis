<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id         = $_POST['id'] ?? '';
    $room_id   = $_POST['room_id'] ?? '';
    $service_id = $_POST['service_id'] ?? '';

    if ($id == "" || $room_id == "" || $service_id == "") {
        echo json_encode([
            "status" => false,
            "message" => "All fields are required"
        ]);
        exit;
    }

    // Duplicate check
    $check = "SELECT id FROM rooms_services 
              WHERE room_id='$room_id' 
              AND service_id='$service_id' 
              AND id!='$id'";
    $res = mysqli_query($con, $check);

    if (mysqli_num_rows($res) > 0) {
        echo json_encode([
            "status" => false,
            "message" => "This service is already assigned to the room category"
        ]);
        exit;
    }

    $sql = "UPDATE rooms_services SET
                room_id='$room_id',
                service_id='$service_id'
            WHERE id='$id'";

    if (mysqli_query($con, $sql)) {
        echo json_encode([
            "status" => true,
            "message" => "Room service updated successfully"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Update failed",
            "error" => mysqli_error($con)
        ]);
    }
}
?>
