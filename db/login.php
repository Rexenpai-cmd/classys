<?php
session_start(); // 🔑 Must be at the top

$conn = new mysqli("localhost", "root", "", "newclass");

if ($conn->connect_error) {
    http_response_code(500);
    echo "Database connection failed.";
    exit;
}

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id, email, status, password FROM users WHERE email = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $db_username, $status, $hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $db_username;
        $_SESSION['status'] = $status;

        // 🔀 Redirect based on user status
        if ($status == 1) {
            echo "instructor";
        } else {
            echo "success";
        }
    } else {
        http_response_code(401);
        echo "Incorrect password.";
    }
} else {
    http_response_code(404);
    echo "User not found.";
}

$stmt->close();
$conn->close();
?>
