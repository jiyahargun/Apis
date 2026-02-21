<?php
include('connect.php');

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$phone = $_POST['phone'];

if($name=="" || $email=="" || $password=="" || $phone==""){
    echo "All fields required";
    exit;
}

// Check duplicate email
$stmt = $con->prepare("SELECT id FROM users WHERE email=?");
$stmt->bind_param("s",$email);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    echo "Email already exists";
    exit;
}

// Insert without password_hash
$stmt = $con->prepare("INSERT INTO users (name,email,password,phone) VALUES (?,?,?,?)");
$stmt->bind_param("ssss",$name,$email,$password,$phone);

if($stmt->execute()){
    echo "User register successfully";
}
?>
