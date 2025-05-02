<?php
require 'db.php'; // Adjust the path as necessary

// Select all users
$result = $conn->query("SELECT id, password FROM users");

while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $plainPassword = $row['password'];

    // Hash the password
    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

    // Update the user with the hashed password
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashedPassword, $id);
    $stmt->execute();
}

echo "Passwords have been hashed successfully.";

// Close the database connection
$conn->close();
?>