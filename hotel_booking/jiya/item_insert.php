<?php

include('connect.php');

$seller_id  = $_POST['seller_id'] ?? null;
$item_name  = $_POST['item_name'];
$item_price = $_POST['item_price'];

if ($item_name == "" || $item_price == "") {
    echo json_encode([
        'status' => 'error',
        'message' => 'Item name and price are required.'
    ]);
    exit;
}

if (!empty($seller_id)) {
    $query = "INSERT INTO menu_items (seller_id, item_name, item_price) 
              VALUES ('$seller_id', '$item_name', '$item_price')";
} else {
    $query = "INSERT INTO menu_items (item_name, item_price) 
              VALUES ('$item_name', '$item_price')";
}

if (mysqli_query($con, $query)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Item inserted successfully'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to insert item',
        'error' => mysqli_error($con)
    ]);
}

mysqli_close($con);
?>
