<?php
header('Content-Type: application/json');
require 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deviceId = $_POST['device_id'];

    $sql = "DELETE FROM devices WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $deviceId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Device deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting device: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>