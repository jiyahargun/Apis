<?php

include('connect.php');

$name = $_POST['seller_name'];
$contact = $_POST['contact'];
$address = $_POST['address'];

if (empty($name) || empty($contact) || empty($address)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'All fields are required.'
    ]);
    exit;
}

$sql = "INSERT INTO sellers (seller_name, contact, address)
        VALUES ('$name', '$contact', '$address')";

$insert = mysqli_query($con, $sql);

if ($insert) {
    echo json_encode([
        'status' => 'success',
        'message' => 'user seller insert successfully.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'user seller insert failed.'
    ]);
}

mysqli_close($con);

?>
