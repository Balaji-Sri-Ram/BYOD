<?php
// Database connection details (replace with your actual credentials)

$conn = new mysqli("localhost", "root", "", "byod");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // Basic email validation (you should use more robust validation)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    // Check if the email exists in the database (very basic example)
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // In a real application, you would generate a unique token,
        // store it in the database linked to the user's ID, and
        // send an email with a reset link.

        echo "A password reset link has been sent to your email address (this is a simplified example).";
    } else {
        echo "No user found with that email address.";
    }

    $stmt->close();
}

$conn->close();
?>