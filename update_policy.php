<?php
header('Content-Type: application/json');
require 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['policy_name']) && isset($_POST['policy_value'])) {
        $policyName = trim($_POST['policy_name']);
        $policyValue = trim($_POST['policy_value']);

        if (empty($policyName)) {
            echo json_encode(['success' => false, 'message' => 'Policy name cannot be empty.']);
            exit;
        }

        $sql = "UPDATE security_policies SET policy_value = ? WHERE policy_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $policyValue, $policyName);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                // Log the activity
                $logMessage = 'Security policy updated: ' . htmlspecialchars($policyName);
                $logSql = "INSERT INTO activity_log (activity_type, message) VALUES ('policy_updated', ?)";
                $logStmt = $conn->prepare($logSql);
                $logStmt->bind_param("s", $logMessage);
                if ($logStmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Security policy "' . htmlspecialchars($policyName) . '" updated successfully.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Security policy updated, but failed to log activity: ' . $logStmt->error]);
                }
                $logStmt->close();
            } else {
                echo json_encode(['success' => false, 'message' => 'Policy "' . htmlspecialchars($policyName) . '" not found or no changes made.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating security policy: ' . $stmt->error]);
        }

        $stmt->close();

    } else {
        echo json_encode(['success' => false, 'message' => 'Policy name and value are required.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method. Please use POST to update policies.']);
}

$conn->close();
?>