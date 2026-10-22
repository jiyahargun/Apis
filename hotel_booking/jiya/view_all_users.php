<?php
include('connect.php');

$sql = "SELECT id, name, email, password, phone 
        FROM hotel_users 
        ORDER BY id DESC";

$result = mysqli_query($con, $sql);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
?>