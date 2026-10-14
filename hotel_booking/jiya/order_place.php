<?php
include('connect.php');

header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"));

if (
    !isset($input->seller_id) ||
    !isset($input->total_amount) ||
    !isset($input->items)
) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request data"
    ]);
    exit;
}

$sellerId = $input->seller_id;
$totalAmount = $input->total_amount;
$itemList = $input->items;

$response = [];

$insertOrder = "INSERT INTO orders (seller_id, total_amount)
                VALUES ('$sellerId', '$totalAmount')";

if (mysqli_query($con, $insertOrder)) {

    $newOrderId = mysqli_insert_id($con);
    $itemStatus = true;

    foreach ($itemList as $row) {
        $itemId = $row->item_id;
        $quantity = $row->quantity;
        $price = $row->price;

        $insertItem = "INSERT INTO order_items (order_id, item_id, quantity, price)
                       VALUES ('$newOrderId', '$itemId', '$quantity', '$price')";

        if (!mysqli_query($con, $insertItem)) {
            $itemStatus = false;
            break;
        }
    }

    if ($itemStatus) {
        $response = [
            "success" => true,
            "order_id" => $newOrderId
        ];
    } else {
        $response = [
            "success" => false,
            "message" => "Order items insert failed"
        ];
    }

} else {
    $response = [
        "success" => false,
        "message" => "Order creation failed"
    ];
}

echo json_encode($response);
?>
