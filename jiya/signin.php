<?php

    include('connect.php');

    $email = $_REQUEST['email'];
    $pass = $_REQUEST['password'];

    $sql ="select * from jiya_user where email = '$email' and password='$pass'";
    $result = mysqli_query($con, $sql);

    $num=mysqli_num_rows($result);

    if($num> 0)
    {
        $fetch=mysqli_fetch_object($result);
        echo json_encode(['code'=>200]);
    }
    else
    {
        echo "0";
    }



?>