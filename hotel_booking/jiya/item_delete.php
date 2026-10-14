<?php
include('connect.php');

$id = $_POST['id'];

if ($id == "") {
    echo json_encode([
        'status' => 'error',
        'message' => 'Item ID is required.'
    ]);
    exit;
}

$query = "DELETE FROM menu_items WHERE id = '$id'";

if (mysqli_query($con, $query)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Item deleted successfully.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to delete item.',
        'error' => mysqli_error($con)
    ]);
}

// Close the database connection
mysqli_close($con);
?>
