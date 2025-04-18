<?php
$conn = new mysqli("localhost", "root", "", "byod");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password_raw = $_POST['password'];
$password = password_hash($password_raw, PASSWORD_DEFAULT);

$check = $conn->prepare("SELECT id FROM users WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "<script>
        alert('Username already exists!');
        window.location.href='login2.html';
    </script>";
} else {
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        echo "<script>
            alert('Registered successfully!');
            window.location.href='index.html';
        </script>";
    } else {
        echo "<script>
            alert('Registration failed. Please try again.');
            window.location.href='login2.html';
        </script>";
    }

    $stmt->close();
}

$check->close();
$conn->close();
?>
