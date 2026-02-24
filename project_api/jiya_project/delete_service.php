<?php
include('connect.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST['id'] ?? '';

    if (empty($id)) {
        echo json_encode([
            "status" => false,
            "message" => "Service id required"
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

    // Delete service
    $stmt = $con->prepare("DELETE FROM services WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => true,
            "message" => "Service deleted successfully"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Delete failed"
        ]);
    }

    $stmt->close();
    $check->close();
    $con->close();
}
?>
