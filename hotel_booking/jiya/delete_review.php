<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST['id'] ?? '';

    if ($id == "") {
        echo json_encode([
            "status" => false,
            "message" => "Review id required"
        ]);
        exit;
    }

    $sql = "DELETE FROM reviews WHERE id='$id'";

    if (mysqli_query($con, $sql)) {

        if (mysqli_affected_rows($con) > 0) {
            echo json_encode([
                "status" => true,
                "message" => "Review deleted successfully"
            ]);
        } else {
            echo json_encode([
                "status" => false,
                "message" => "No record found"
            ]);
        }

    } else {
        echo json_encode([
            "status" => false,
            "message" => "Delete failed",
            "error" => mysqli_error($con)
        ]);
    }
}
?>
