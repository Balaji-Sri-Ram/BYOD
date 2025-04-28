<?php
header('Content-Type: application/json');
require 'db_config.php';

$sql = "SELECT id, device_name, owner_name, mac_address, status, DATE_FORMAT(last_active, '%Y-%m-%d %H:%i:%s') AS last_active FROM devices";
$result = $conn->query($sql);

$devices = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $devices[] = $row;
    }
}

$conn->close();
echo json_encode($devices);
?>