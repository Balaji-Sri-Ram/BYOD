<<<<<<< HEAD
<?php
header('Content-Type: application/json');
require 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['name'], $data['owner'], $data['mac'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    $deviceName = $data['name'];
    $ownerName = $data['owner'];
    $macAddress = $data['mac'];
    $defaultStatus = 'active';

    $sql = "INSERT INTO devices (device_name, owner_name, mac_address, status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $deviceName, $ownerName, $macAddress, $defaultStatus);

    if ($stmt->execute()) {
        // Log the activity
        $logMessage = 'New device registered: ' . htmlspecialchars($deviceName);
        $logSql = "INSERT INTO activity_log (activity_type, message) VALUES ('device_registered', ?)";
        $logStmt = $conn->prepare($logSql);
        $logStmt->bind_param("s", $logMessage);
        $logStmt->execute();
        $logStmt->close();

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
=======
<?php
header('Content-Type: application/json');
require 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['name'], $data['owner'], $data['mac'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    $deviceName = $data['name'];
    $ownerName = $data['owner'];
    $macAddress = $data['mac'];

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
>>>>>>> ce7708e3aea7bf4ad8e2f9a8a73612b8478d9a94
