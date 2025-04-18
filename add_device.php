<?php
header('Content-Type: application/json');
require 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deviceName = $_POST['device_name'];
    $ownerName = $_POST['owner_name'];
    $macAddress = $_POST['mac_address'];

    $sql = "INSERT INTO devices (device_name, owner_name, mac_address) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $deviceName, $ownerName, $macAddress);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Device registered successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error registering device: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>