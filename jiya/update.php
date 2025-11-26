<?php
    include('connect.php');

    $id = $_POST['id'];
    $p_name = $_POST['p_name'];
    $p_price = $_POST['p_price'];
    $p_des = $_POST['p_des'];
   


    $Sql_Query = "UPDATE jiya_products SET p_name='$p_name', p_price='$p_price', p_des='$p_des' WHERE id = '$id'";

        if(mysqli_query($con,$Sql_Query))
        {
            echo 'Record Updated Successfully';
        }

        else
        {
            echo 'Something went wrong';
        }

    mysqli_close($con);
?>