<?php
include('connect.php');

$id = $_POST['id'];
$name = $_POST['city_name'];

$sql = "UPDATE cities 
        SET city_name='$name' 
        WHERE id='$id'";

if(mysqli_query($con,$sql))
{
    echo json_encode(["message"=>"City Updated sucessfully"]);
}
else
{
    echo json_encode(["message"=>"Error"]);
}
?>
