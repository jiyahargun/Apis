<?php

include('connect.php');

$seller_id = $_POST['seller_id'];
$name      = $_POST['item_name'];
$price     = $_POST['item_price'];

// Empty check
if (empty($seller_id) || empty($name) || empty($price)) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'All fields are required.'
    ]);
    exit;
}

$sql = "INSERT INTO menu_items (seller_id, item_name, item_price)
        VALUES ('$seller_id', '$name', '$price')";

$insert = mysqli_query($con, $sql);

if ($insert) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Item added.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Insert failed.'
    ]);
}

mysqli_close($con);

?>
