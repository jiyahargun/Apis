<?php
include('connect.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST['id'] ?? '';
    $service_name = trim($_POST['service_name'] ?? '');

    if (empty($id) || empty($service_name)) {
        echo json_encode([
            "status" => false,
            "message" => "Id and service name required"
        ]);
        exit;
    }

    // Check if service exists
    $check = $con->prepare("SELECT id FROM services WHERE id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows == 0) {
        echo json_encode([
            "status" => false,
            "message" => "Service not found"
        ]);
        exit;
    }

    // Update service
    $stmt = $con->prepare("UPDATE services SET service_name = ? WHERE id = ?");
    $stmt->bind_param("si", $service_name, $id);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => true,
            "message" => "Service updated successfully"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Update failed"
        ]);
    }

    $stmt->close();
    $check->close();
    $con->close();
}
?>
