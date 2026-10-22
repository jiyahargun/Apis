<?php
include('connect.php');

$id = $_POST['id'];

if($id == ""){
    echo json_encode(["code"=>400,"message"=>"Offer ID required"]);
    exit;
}

// current status
$get = mysqli_query($con, "SELECT offer_status FROM offers WHERE id='$id'");
$row = mysqli_fetch_assoc($get);

$current_status = $row['offer_status'];

// toggle
$new_status = ($current_status == 1) ? 0 : 1;

// update
$update = mysqli_query(
    $con,
    "UPDATE offers SET offer_status='$new_status' WHERE id='$id'"
);

if($update){
    echo json_encode([
        "code"=>200,
        "message"=>"Offer status updated",
        "status"=>$new_status
    ]);
}else{
    echo json_encode(["code"=>400,"message"=>"Failed"]);
}
?>
