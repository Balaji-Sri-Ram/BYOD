<<<<<<< HEAD
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
=======
<?php
// Database connection details (replace with your actual credentials)
$conn = new mysqli("localhost", "username", "password", "database_name");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST["token"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    // In a real application, you would:
    // 1. Verify the token against the database.
    // 2. Check if the passwords match.
    // 3. Hash the new password.
    // 4. Update the user's password in the database.
    // 5. Invalidate the token.

    if ($new_password === $confirm_password) {
        // In a real application, hash the password before updating
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Basic update query (replace with your actual table and column names)
        // You would also need to use the token to identify the user
        // and ensure the token is still valid.
        $sql = "UPDATE users SET password = ? WHERE /* condition based on token */";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $hashed_password);
        // Bind the token parameter here
        // $stmt->execute();

        echo "Password reset successfully! (This is a simplified example)";
    } else {
        echo "Passwords do not match.";
    }
} else if (isset($_GET['token'])) {
    // Display the reset password form with the token
    // You might want to also verify the token here before showing the form
    include 'reset_password.html';
    exit();
}

$conn->close();
>>>>>>> ce7708e3aea7bf4ad8e2f9a8a73612b8478d9a94
?>