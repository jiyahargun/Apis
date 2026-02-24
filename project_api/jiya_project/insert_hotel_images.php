<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $hotel_id = $_POST['hotel_id'];
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
        $url = "http://localhost/jiya_project/" . $file_path;

        $query = "INSERT INTO hotel_images (hotel_id, image) 
                  VALUES ('$hotel_id', '$url')";

        mysqli_query($con, $query);

        echo json_encode([
            "status" => true,
            "image" => $url
        ]);
    }
}
?>
