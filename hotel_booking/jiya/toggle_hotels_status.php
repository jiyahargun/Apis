<?php
include('connect.php');

$id = $_POST['id'];

if($id == ""){
    echo json_encode(["code"=>400,"message"=>"Hotel ID required"]);
    exit;
}

// pehle current status nikalo
$get = mysqli_query($con, "SELECT hotel_status FROM hotels WHERE id='$id'");
$row = mysqli_fetch_assoc($get);

$current_status = $row['hotel_status'];

// toggle logic
$new_status = ($current_status == 1) ? 0 : 1;

// update status
$update = mysqli_query(
    $con,
    "UPDATE hotels SET hotel_status='$new_status' WHERE id='$id'"
);

if($update){
    echo json_encode([
        "code"=>200,
        "message"=>"Hotel status updated",
        "status"=>$new_status
    ]);
}else{
    echo json_encode(["code"=>400,"message"=>"Failed"]);
}
?>
