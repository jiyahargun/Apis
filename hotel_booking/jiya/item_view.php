<?php
include('connect.php');

$query = "SELECT * FROM menu_items ORDER BY id";
$result = mysqli_query($con, $query);

$items = [];

while ($row = mysqli_fetch_assoc($result)) {
    $items[] = $row;
}

echo json_encode($items);
?>
