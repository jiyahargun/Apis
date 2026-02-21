<?php
include 'connect.php';

$data = [];

if (isset($_GET['offer_id']) && $_GET['offer_id'] != '') {

    $offer_id = mysqli_real_escape_string($con, $_GET['offer_id']);

    $sql = "SELECT * FROM offers_images WHERE offer_id='$offer_id'";
} else {
    $sql = "SELECT * FROM offers_images";
}

$query = mysqli_query($con, $sql);

if ($query) {
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }

    echo json_encode([
        "status" => true,
        "data" => $data
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Query failed"
    ]);
}
?>
