<?php
include('connect.php');

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if ($email == "" || $password == "") {
    echo "All fields required";
    exit;
}

$stmt = $con->prepare("SELECT id FROM admins WHERE email=? AND password=?");
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Login Success";
} else {
    echo "Invalid Email or Password";
}
exit;
?>
