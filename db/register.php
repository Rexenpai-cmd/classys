<?php
// db/register.php

$conn = new mysqli("localhost", "root", "", "newclass");

if ($conn->connect_error) {
    http_response_code(500);
    echo "Database connection failed.";
    exit;
}

$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];

$stmt = $conn->prepare("INSERT INTO users (email, password, name, lname) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $email, $password, $firstname, $lastname);

if ($stmt->execute()) {
    echo "Registration successful!";
} else {
    http_response_code(400);
    echo "Registration failed. Email might already be in use.";
}

$stmt->close();
$conn->close();
?>
