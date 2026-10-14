<?php

    include('connect.php');

   $uname = $_POST['name'];
   $email = $_POST['email'];
   $mobile = $_POST['mobile'];
   $pass = $_POST['password'];

    if($name=="" && $email==""  && $mobile=="" && $pass== "")
    {
        echo '0';
    }
    else
    {
        $sql = "insert into jiya_user (email,password,name,mobile)values('$email','$pass','$name','$mobile')";
        mysqli_query($con,$sql);
    }

?>