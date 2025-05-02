<?php
// db/register.php

$conn = new mysqli("localhost", "root", "", "newclass");

if ($conn->connect_error) {
    http_response_code(500);
    echo "Database connection failed.";
    exit;
}

// Get the form data
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];

// Get the current timestamp
$timestamp = date('Y-m-d H:i:s');

// Set the status value
$status = 1;

// Prepare and bind the query to insert the data
$stmt = $conn->prepare("INSERT INTO users (email, password, name, lname, timestamp, status) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssi", $email, $password, $firstname, $lastname, $timestamp, $status);

if ($stmt->execute()) {
    echo "Registration successful!";
} else {
    http_response_code(400);
    echo "Registration failed. Email might already be in use.";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>