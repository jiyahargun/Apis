<?php
header("Content-Type: application/json");
include "connect.php";

try {

    //  Current month & year
    $currentMonth = date('m');
    $currentYear  = date('Y');

    //  Total Hotels
    $totalHotels = $con->query("
        SELECT COUNT(*) AS total 
        FROM hotels
    ")->fetch_assoc()['total'];

    //  Total Rooms
    $totalRooms = $con->query("
        SELECT COUNT(*) AS total 
        FROM rooms
    ")->fetch_assoc()['total'];

    //  New Bookings This Month
    $stmtBookings = $con->prepare("
        SELECT COUNT(*) AS total
        FROM bookings
        WHERE MONTH(created_at) = ?
          AND YEAR(created_at) = ?
    ");
    $stmtBookings->bind_param("ii", $currentMonth, $currentYear);
    $stmtBookings->execute();
    $newBookings = $stmtBookings->get_result()
                                ->fetch_assoc()['total'];

    //  Earnings This Month (only paid)
    $stmtEarnings = $con->prepare("
        SELECT IFNULL(SUM(amount),0) AS total
        FROM payments
        WHERE payment_status = 1
          AND MONTH(created_at) = ?
          AND YEAR(created_at) = ?
    ");
    $stmtEarnings->bind_param("ii", $currentMonth, $currentYear);
    $stmtEarnings->execute();
    $earnings = $stmtEarnings->get_result()
                             ->fetch_assoc()['total'];

    echo json_encode([
        "status" => true,
        "data" => [
            "total_hotels" => (int)$totalHotels,
            "total_rooms" => (int)$totalRooms,
            "new_bookings_this_month" => (int)$newBookings,
            "earnings_this_month" => (float)$earnings
        ]
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => false,
        "message" => $e->getMessage()
    ]);
}