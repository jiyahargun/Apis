<?php

    include('connect.php');

    $p_name = $_POST['p_name'];
    $p_price = $_POST['p_price'];
    $p_des = $_POST['p_des'];

    if($p_name=="" && $p_price=="" && $p_des== "")
    {
        echo '0';
    }
    else
    {
        $sql = "insert into jiya_products (p_name,p_price,p_des)values('$p_name','$p_price','$p_des')";
        mysqli_query($con,$sql);
    }

?>