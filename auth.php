<?php
$conn = new mysqli("localhost", "root", "", "byod");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['password'];

    if (password_verify($password, $hashed_password)) {
        session_start();
        $_SESSION['username'] = $username;
        echo "<script>
            alert('Login successfully!');
            window.location.href='index.html';
            </script>";
        // header("Location: index.html");
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

$conn->close();
?>
