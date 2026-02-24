<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id         = $_POST['id'] ?? '';
    $hotel_id   = $_POST['hotel_id'] ?? '';
    $service_id = $_POST['service_id'] ?? '';

    if ($id == "" || $hotel_id == "" || $service_id == "") {
        echo json_encode([
            "status" => false,
            "message" => "All fields are required"
        ]);
        exit;
    }

    // Duplicate check
    $check = "SELECT id FROM hotel_service 
              WHERE hotel_id='$hotel_id' 
              AND service_id='$service_id' 
              AND id!='$id'";
    $res = mysqli_query($con, $check);

    if (mysqli_num_rows($res) > 0) {
        echo json_encode([
            "status" => false,
            "message" => "This service is already assigned to the hotel"
        ]);
        exit;
    }

    $sql = "UPDATE hotel_service SET
                hotel_id='$hotel_id',
                service_id='$service_id'
            WHERE id='$id'";

    if (mysqli_query($con, $sql)) {
        echo json_encode([
            "status" => true,
            "message" => "Hotel service updated successfully"
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
