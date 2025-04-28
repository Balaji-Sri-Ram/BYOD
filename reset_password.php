<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "byod";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"] ?? '';
    $new_password = $_POST["new_password"] ?? '';

    if (!empty($email) && !empty($new_password)) {
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            $response = ["success" => false, "message" => "Database connection failed: " . $conn->connect_error];
            echo json_encode($response);
            exit();
        }

        $email = $conn->real_escape_string($email);
        $new_password = $conn->real_escape_string($new_password);

        $sql_check_email = "SELECT id FROM users WHERE email = '$email'";
        $result = $conn->query($sql_check_email);

        if ($result->num_rows > 0) {
          
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $sql_update_password = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";

            if ($conn->query($sql_update_password) === TRUE) {
                $response = ["success" => true, "message" => "Password reset successfully. You can now log in with your new password."];
            } else {
                $response = ["success" => false, "message" => "Error updating password: " . $conn->error];
            }
        } else {
            $response = ["success" => false, "message" => "Email not found in our records."];
        }

        $conn->close();
    } else {
        $response = ["success" => false, "message" => "Please provide both email and new password."];
    }

    echo json_encode($response);
} else {
    $response = ["success" => false, "message" => "Invalid request method."];
    echo json_encode($response);
}
?>