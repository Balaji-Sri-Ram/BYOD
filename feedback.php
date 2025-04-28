<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "byod";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo "Database connection failed: " . $conn->connect_error;
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating = $_POST["rating"] ?? null;
    $category = htmlspecialchars(trim($_POST["category"] ?? ''));
    $feedback = htmlspecialchars(trim($_POST["feedback"] ?? ''));

    if ($rating === null || empty($category) || empty($feedback)) {
        echo "Please fill in all required fields.";
        exit;
    }

    $rating = filter_var($rating, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 5]]);
    if ($rating === false) {
        echo "Invalid rating value.";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO feedback (rating, category, feedback) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $rating, $category, $feedback);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error saving feedback: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request method.";
}

$conn->close();
?>