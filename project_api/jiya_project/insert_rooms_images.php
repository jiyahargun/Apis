<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $room_category_id = $_POST['room_category_id']; // remove trailing space
    $file_name = $_FILES['image']['name'];
    $temp_name = $_FILES['image']['tmp_name'];

    $folder = "rooms/";

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $new_name = time() . "_" . $file_name;
    $file_path = $folder . $new_name;

    if (move_uploaded_file($temp_name, $file_path)) {

        //  FULL URL BANANA
       $upload_url = 'https://'.$_SERVER['SERVER_NAME'] . "/jiya_project/" . $file_path;

        $query = "INSERT INTO room_images (room_category_id, image) 
          VALUES ('$room_category_id', '$upload_url')";

        mysqli_query($con, $query);

        echo json_encode([
            "status" => true,
            "image" => $upload_url
        ]);
    }
}
?>
