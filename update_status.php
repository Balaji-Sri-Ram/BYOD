<<<<<<< HEAD
<?php
header('Content-Type: application/json');
require 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deviceId = $_POST['device_id'];
    $newStatus = $_POST['status'];

    if ($newStatus !== 'active' && $newStatus !== 'inactive') {
        echo json_encode(['success' => false, 'message' => 'Invalid status value']);
        exit();
    }

    $sql = "UPDATE devices SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newStatus, $deviceId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Device status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating device status: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
=======
<?php
header('Content-Type: application/json');
require 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deviceId = $_POST['device_id'];
    $newStatus = $_POST['status'];

    if ($newStatus !== 'active' && $newStatus !== 'inactive') {
        echo json_encode(['success' => false, 'message' => 'Invalid status value']);
        exit();
    }

    $sql = "UPDATE devices SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newStatus, $deviceId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Device status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating device status: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
>>>>>>> ce7708e3aea7bf4ad8e2f9a8a73612b8478d9a94
?>