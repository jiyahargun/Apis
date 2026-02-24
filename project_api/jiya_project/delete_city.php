<?php
include('connect.php');

$id = $_POST['id'];

$sql = "DELETE FROM cities WHERE id='$id'";

if(mysqli_query($con,$sql))
{
    echo json_encode(["message"=>"City Deleted successfully"]);
}
else
{
    echo json_encode(["message"=>"Error deleting city"]);
}
?>
