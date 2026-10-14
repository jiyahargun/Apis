<?php
include('connect.php');

$id = $_POST['id'];
$item_name = $_POST['item_name'];
$item_price = $_POST['item_price'];
$seller_id = $_POST['seller_id'] ?? null;

if ($id == "" || $item_name == "" || $item_price == "") {
    echo json_encode([
        'status' => 'error',
        'message' => 'Required fields are missing.'
    ]);
    exit;
}

if (!empty($seller_id)) {
    $query = "UPDATE menu_items 
              SET item_name = '$item_name',
                  item_price = '$item_price',
                  seller_id = '$seller_id'
              WHERE id = '$id'";
} else {
    $query = "UPDATE menu_items 
              SET item_name = '$item_name',
                  item_price = '$item_price',
                  seller_id = NULL
              WHERE id = '$id'";
}

if (mysqli_query($con, $query)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Item updated successfully.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to update item.',
        'error' => mysqli_error($con)
    ]);
}

mysqli_close($con);
?>
