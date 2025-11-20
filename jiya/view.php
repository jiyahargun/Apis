
<?php

    include('connect.php');

    $sql ="select * from jiya_products ";
    $response = array();
    $res = mysqli_query($con,$sql);

    while($data = mysqli_fetch_array($res))
    {
        $row["id"]=$data["id"];
        $row["p_name"]=$data["p_name"];
        $row["p_price"]=$data["p_price"];
         $row["p_des"]=$data["p_des"];

        array_push($response,$row);


    }
    echo json_encode($response);



?>
