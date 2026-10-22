<?php
include('connect.php');

$sql = "SELECT * FROM offers WHERE offer_status = 1";

$result = mysqli_query($con, $sql);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

if (!empty($data)) {
    echo json_encode([
        "code" => 200,
        "status" => "success",
        "offers" => $data
    ]);
} else {
    echo json_encode([
        "code" => 404,
        "status" => "No available offers found"
    ]);
}
?>
