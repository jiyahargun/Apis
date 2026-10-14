<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "status" => false,
        "message" => "Invalid request method"
    ]);
    exit;
}

$id = $_POST['id'] ?? '';

if ($id === '') {
    echo json_encode([
        "status" => false,
        "message" => "Image id required"
    ]);
    exit;
}

/* Get image path first (optional but recommended) */
$get = mysqli_query($con, "SELECT image FROM offers_images WHERE id='$id'");
if (mysqli_num_rows($get) == 0) {
    echo json_encode([
        "status" => false,
        "message" => "Image not found"
    ]);
    exit;
}

$row = mysqli_fetch_assoc($get);
$imagePath = $row['image'];

/* Delete from DB */
$delete = mysqli_query($con, "DELETE FROM offers_images WHERE id='$id'");

if ($delete) {

    // Delete image file from server (if exists)
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

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
?>
