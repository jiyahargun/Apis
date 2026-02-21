<?php
include('connect.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $service_name = trim($_POST['service_name'] ?? '');

    if (empty($service_name)) {
        echo json_encode([
            "status" => false,
            "message" => "Service name required"
        ]);
        exit;
    }

    // Duplicate check
    $check = $con->prepare("SELECT id FROM services WHERE service_name = ?");
    $check->bind_param("s", $service_name);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo json_encode([
            "status" => false,
            "message" => "Service already exists"
        ]);
        exit;
    }

    $stmt = $con->prepare("INSERT INTO services (service_name) VALUES (?)");
    $stmt->bind_param("s", $service_name);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => true,
            "message" => "Service added successfully"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Something went wrong"
        ]);
    }

    $stmt->close();
    $check->close();
    $con->close();
}
?>
