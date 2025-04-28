<<<<<<< HEAD
<?php
header('Content-Type: application/json');
require 'db_config.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data['mac'])) {
    $mac = $conn->real_escape_string($data['mac']);

    $sql = "DELETE FROM devices WHERE mac_address = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $mac);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Device deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting device: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request or MAC missing']);
}

$conn->close();
?>
=======
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
>>>>>>> ce7708e3aea7bf4ad8e2f9a8a73612b8478d9a94
