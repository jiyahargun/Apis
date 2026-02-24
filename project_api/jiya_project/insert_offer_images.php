<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $offer_id = $_POST['offer_id'];
    $file_name = $_FILES['image']['name'];
    $temp_name = $_FILES['image']['tmp_name'];

    $folder = "uploads/";

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $new_name = time() . "_" . $file_name;
    $file_path = $folder . $new_name;

    if (move_uploaded_file($temp_name, $file_path)) {

        //  FULL URL BANANA
       $upload_url = 'https://'.$_SERVER['SERVER_NAME'] . "/jiya_project/" . $file_path;

        $query = "INSERT INTO offers_images (offer_id, image) 
                  VALUES ('$offer_id', '$upload_url')";

        mysqli_query($con, $query);

        echo json_encode([
            "status" => true,
            "image" => $upload_url
        ]);
    }
}
?>
