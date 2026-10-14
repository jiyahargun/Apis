<?php
include 'connect.php';

$sql = "SELECT 
            r.id,
            r.room_number,

            h.id AS hotel_id,
            h.hotel_name,

            rc.id AS room_category_id,
            rc.room_type,
            rc.price,
            rc.total_rooms,
            rc.available_rooms,
            rc.description,
            rc.room_status

        FROM rooms r
        LEFT JOIN hotels h ON r.hotel_id = h.id
        LEFT JOIN room_category rc ON r.room_category_id = rc.id
        ORDER BY r.id DESC";

$result = mysqli_query($con, $sql);

if ($result) {

    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode([
        "status" => true,
        "data" => $data
    ]);

} else {
    echo json_encode([
        "status" => false,
        "message" => "Failed",
        "error" => mysqli_error($con)
    ]);
}
?>
