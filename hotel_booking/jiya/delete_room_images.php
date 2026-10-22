<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!isset($_POST['id'])) {
        echo json_encode([
            "status" => false,
            "message" => "Image id missing"
        ]);
        exit;
    }

    $id = $_POST['id'];

    $delete = mysqli_query($con, 
        "DELETE FROM room_images WHERE id='$id'"
    );

    if ($delete) {
        echo json_encode([
            "status" => true,
            "message" => "Image deleted successfully"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Delete failed"
        ]);
    }

} else {
    echo json_encode([
        "status" => false,
        "message" => "Invalid request"
    ]);
}
?>
