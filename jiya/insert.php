<?php

include 'connect.php';


$upload_path = 'images/';



//creating the upload url
$upload_url = 'https://' . $_SERVER['SERVER_NAME'] . "/API/" . $upload_path;

//getting name from the request
$p_name  = $_REQUEST['p_name'];
$p_price = $_REQUEST['p_price'];
$p_des   = $_REQUEST['p_des'];

//getting file info from the request (FIXED)
$fileinfo = pathinfo($_FILES["p_img"]["p_name"]);

//getting the file extension
$extension = $fileinfo["extension"];

//random file name
$random = 'image_' . rand(1000,9999);

//file url to store in the database
$file_url = $upload_url . $random . '.' . $extension;

//file path to upload in the server
$file_path = $upload_path . $random . '.' . $extension;

//saving the file
move_uploaded_file($_FILES["p_img"]["p_name"], $file_path);

//SQL INSERT (FIXED: jiya_products)
$sql = "INSERT INTO jiya_products(p_name, p_price, p_des, p_img) 
        VALUES ('$p_name', '$p_price', '$p_des', '$file_url')";

$ex = mysqli_query($con, $sql);

//closing the connection
mysqli_close($con);

?>
