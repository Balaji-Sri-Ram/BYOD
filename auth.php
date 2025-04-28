<?php
session_start();
$conn = new mysqli("localhost", "root", "", "byod");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = trim($_POST['username']);
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        $_SESSION['username'] = $username;
        echo "<script>
            alert('Login successfully!');
            window.location.href='index.html';
        </script>";
        exit();
    } else {
        echo "<script>
            alert('Incorrect password!');
            window.location.href='login1.html';
        </script>";
    }
} else {
    echo "<script>
        alert('User not found!');
        window.location.href='login1.html';
    </script>";
}

$stmt->close();
$conn->close();
?>
