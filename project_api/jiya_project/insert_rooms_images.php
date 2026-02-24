<?php
include 'connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // 1️⃣ Validate room_category_id
    $room_category_id = isset($_POST['room_category_id']) ? $_POST['room_category_id'] : null;

    if (!$room_category_id) {
        echo json_encode([
            "status" => false,
            "message" => "Room category ID is required"
        ]);
        exit;
    }

    // 2️⃣ Check if room_category_id exists in the DB
    $check = mysqli_query($con, "SELECT id FROM room_category WHERE id = '$room_category_id'");
    if(mysqli_num_rows($check) == 0){
        echo json_encode([
            "status" => false,
            "message" => "Invalid room category ID"
        ]);
        exit;
    }

    // 3️⃣ Check if image(s) uploaded
    if (!isset($_FILES['image'])) {
        echo json_encode([
            "status" => false,
            "message" => "No image uploaded"
        ]);
        exit;
    }

    // 4️⃣ Prepare upload folder
    $folder = "uploads/";
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $responses = [];
    // Handle single or multiple uploads
    $images = $_FILES['image'];
    $total_files = is_array($images['name']) ? count($images['name']) : 1;

    for ($i = 0; $i < $total_files; $i++) {

        $file_name = is_array($images['name']) ? $images['name'][$i] : $images['name'];
        $temp_name = is_array($images['tmp_name']) ? $images['tmp_name'][$i] : $images['tmp_name'];

        $new_name = time() . "_" . rand(1000,9999) . "_" . $file_name; // Unique name
        $file_path = $folder . $new_name;

        if (move_uploaded_file($temp_name, $file_path)) {

            $upload_url = 'https://'.$_SERVER['SERVER_NAME'] . "/jiya_project/" . $file_path;

            $query = "INSERT INTO room_images (room_category_id, image) 
                      VALUES ('$room_category_id', '$upload_url')";

            if(mysqli_query($con, $query)){
                $responses[] = [
                    "status" => true,
                    "image" => $upload_url
                ];
            } else {
                $responses[] = [
                    "status" => false,
                    "message" => mysqli_error($con)
                ];
            }

        } else {
            $responses[] = [
                "status" => false,
                "message" => "Failed to upload $file_name"
            ];
        }
    }

    echo json_encode($responses);
}
?>