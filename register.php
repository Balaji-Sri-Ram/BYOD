<?php
$conn = new mysqli("localhost", "root", "", "byod");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

if ($conn->query($sql) === TRUE) {
    echo "<script>
        alert('Registered successfully!');
        window.location.href='index.html';
        </script>";
    // header("Location: login1.html");
    exit();
} else {
    if ($conn->errno === 1062) {
        echo "Username already exists. Please try another.";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
