<?php
header('Content-Type: application/json');
require 'db_config.php';

if (isset($_GET['status']) && $_GET['status'] === 'active') {
    $sql = "SELECT COUNT(*) AS active_devices FROM devices WHERE status = 'active'";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        echo json_encode(['success' => true, 'count' => $row['active_devices']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error fetching active device count: ' . $conn->error]);
    }
} else {
    $sql = "SELECT COUNT(*) AS total_devices FROM devices";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        echo json_encode(['success' => true, 'count' => $row['total_devices']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error fetching total device count: ' . $conn->error]);
    }
}

$conn->close();
?>