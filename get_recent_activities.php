<?php
header('Content-Type: application/json');
require 'db_config.php';

$sql = "SELECT message, timestamp FROM activity_log ORDER BY timestamp DESC LIMIT 5"; // Adjust LIMIT as needed
$result = $conn->query($sql);

$activities = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $activities[] = [
            'message' => $row['message'],
            'timestamp' => $row['timestamp'] // Returning the raw timestamp for JavaScript to process
        ];
    }
    echo json_encode(['success' => true, 'activities' => $activities]);
} else {
    echo json_encode(['success' => false, 'message' => 'No recent activities found']);
}

$conn->close();
?>