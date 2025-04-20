<?php
// Database connection details
$servername = "localhost"; // Replace with your server name
$username = "your_username"; // Replace with your database username
$password = "your_password"; // Replace with your database password
$dbname = "byod_management"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// --- Backend Functionality ---

// 1. User Registration (assuming you have a registration form)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
  $username = $_POST["username"];
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password for security
  $email = $_POST["email"];

  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $username, $password, $email);

  if ($stmt->execute()) {
    echo "Registration successful!";
    // Redirect to login page or display success message
    header("Location: login.php");
    exit();
  } else {
    echo "Error during registration: " . $stmt->error;
  }
  $stmt->close();
}

// 2. User Login (assuming you have a login form)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
  $username = $_POST["username"];
  $password = $_POST["password"];

  $sql = "SELECT id, username, password FROM users WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row["password"])) {
      // Password is correct, start a session
      session_start();
      $_SESSION["user_id"] = $row["id"];
      $_SESSION["username"] = $row["username"];
      // Redirect to a dashboard or protected page
      header("Location: dashboard.php");
      exit();
    } else {
      echo "Incorrect password.";
    }
  } else {
    echo "User not found.";
  }
  $stmt->close();
}

// 3. Device Registration (example - needs a form to submit device info)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register_device"]) && isset($_SESSION["user_id"])) {
  $device_name = $_POST["device_name"];
  $device_model = $_POST["device_model"];
  $serial_number = $_POST["serial_number"];
  $user_id = $_SESSION["user_id"];

  $sql = "INSERT INTO devices (user_id, device_name, device_model, serial_number, registration_date) VALUES (?, ?, ?, ?, NOW())";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("isss", $user_id, $device_name, $device_model, $serial_number);

  if ($stmt->execute()) {
    echo "Device registered successfully!";
    // Redirect or display success message
  } else {
    echo "Error registering device: " . $stmt->error;
  }
  $stmt->close();
}

// 4. Fetching User's Devices (example for a dashboard)
function getUserDevices($user_id, $conn) {
  $sql = "SELECT id, device_name, device_model, serial_number, registration_date FROM devices WHERE user_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  return $result->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
}

// 5. Enforcing Security Policies (this would be more complex and depend on your policy implementation)
// You might have tables for policies and apply checks when devices try to access resources.

// 6. Activity Monitoring (you'd need to log activities in a database table)
function logActivity($user_id, $device_id, $activity_type, $details, $conn) {
  $sql = "INSERT INTO device_activity (user_id, device_id, activity_type, details, timestamp) VALUES (?, ?, ?, ?, NOW())";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("iiss", $user_id, $device_id, $activity_type, $details);
  $stmt->execute();
  $stmt->close();
}

// 7. Fetching Classroom Devices (if you have a way to associate devices with classrooms)
function getClassroomDevices($classroom_id, $conn) {
  $sql = "SELECT d.device_name, d.device_model, u.username FROM devices d JOIN users u ON d.user_id = u.id WHERE d.classroom_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $classroom_id);
  $stmt->execute();
  $result = $stmt->get_result();
  return $result->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
}

// Close the database connection at the end of your script
$conn->close();
?>