<?php


include('connect.php');

$seller_id = $_GET['seller_id'];
$response = array();

$orderQuery = "SELECT * FROM orders WHERE seller_id = '$seller_id' ORDER BY order_date DESC";
$orderResult = mysqli_query($con, $orderQuery);

$orders = array();

while ($order = mysqli_fetch_assoc($orderResult)) {
    $order_id = $order['id'];

    // Fetch items for this order
    $itemQuery = "SELECT oi.quantity, oi.price, i.item_name 
                  FROM order_items oi 
                  JOIN menu_items i ON oi.item_id = i.id 
                  WHERE oi.order_id = '$order_id'";

    $itemResult = mysqli_query($con, $itemQuery);
    $items = array();

    while ($item = mysqli_fetch_assoc($itemResult)) {
        $items[] = $item;
    }

    $order['items'] = $items;
    $orders[] = $order;
}

$response['orders'] = $orders;
echo json_encode($response);
?>