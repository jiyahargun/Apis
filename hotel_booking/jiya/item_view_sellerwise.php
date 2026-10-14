<?php

include('connect.php');

$sql = "SELECT * FROM menu_items";
$result = $con->query($sql);
$v_items = [];

while ($row = $result->fetch_assoc()) {
    $v_items[] = $row;
}
echo json_encode($v_items);
?>