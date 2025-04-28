<?php
header('Content-Type: application/json');
require 'db_config.php';

$sql = "SELECT device_name, owner_name, mac_address, status, last_active FROM devices ORDER BY last_active DESC LIMIT 5"; // Adjust LIMIT as needed
$result = $conn->query($sql);

$devices = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $devices[] = $row;
    }
    echo json_encode(['success' => true, 'devices' => $devices]);
} else {
    echo json_encode(['success' => false, 'message' => 'No devices found']);
}

$conn->close();
?>