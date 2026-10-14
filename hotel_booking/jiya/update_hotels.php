<?php
include('connect.php');

$id = $_POST['id'] ?? '';
$city_id = $_POST['city_id'] ?? '';
$hotel_name = $_POST['hotel_name'] ?? '';
$address = $_POST['address'] ?? '';
$description = $_POST['description'] ?? '';
$rating = $_POST['rating'] ?? '';

if($id == "" || $city_id == "" || $hotel_name == "" || $address == "" || $description == "" || $rating == ""){
    echo json_encode(["message"=>"All fields required"]);
    exit;
}

$sql = "UPDATE hotels SET 
        hotel_name='$hotel_name',
        address='$address',
        description='$description',
        rating='$rating',
        city_id='$city_id'
        WHERE id='$id'";

if(mysqli_query($con,$sql)){
    echo json_encode(["message"=>"Hotel Updated successfully"]);
}else{
    echo json_encode(["message"=>"Failed to update hotel"]);
}
?>