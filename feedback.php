<<<<<<< HEAD
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
=======
<?php
// Database connection details
$servername = "localhost"; // Replace with your server name
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "byod"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
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

    // Sanitize rating (ensure it's an integer between 1 and 5)
    $rating = filter_var($rating, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 5]]);
    if ($rating === false) {
        echo "Invalid rating value.";
        exit;
    }

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO feedback (rating, category, feedback) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $rating, $category, $feedback);

    // Execute the statement
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error saving feedback: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Invalid request method.";
}

// Close the database connection
$conn->close();
>>>>>>> ce7708e3aea7bf4ad8e2f9a8a73612b8478d9a94
?>