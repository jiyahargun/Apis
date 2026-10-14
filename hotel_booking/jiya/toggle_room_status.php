<?php
include('connect.php');

$id = $_POST['id'];

if($id == ""){
    echo json_encode(["code"=>400,"message"=>"Room ID required"]);
    exit;
}

// current status nikaalo
$get = mysqli_query($con, "SELECT room_status FROM room_category WHERE id='$id'");
$row = mysqli_fetch_assoc($get);

$current_status = $row['room_status'];

// toggle
$new_status = ($current_status == 1) ? 0 : 1;

// update
$update = mysqli_query(
    $con,
    "UPDATE room_category SET room_status='$new_status' WHERE id='$id'"
);

if($update){
    echo json_encode([
        "code"=>200,
        "message"=>"Room status updated",
        "status"=>$new_status
    ]);
}else{
    echo json_encode(["code"=>400,"message"=>"Failed"]);
}
?>
