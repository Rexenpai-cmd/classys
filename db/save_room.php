<?php
    // Database configuration
    $host = 'localhost'; // Database host
    $db = 'newclass'; // Database name
    $user = 'root'; // Database username
    $pass = ''; // Database password

    try {
        // Create a new PDO instance
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get the JSON input
        $input = json_decode(file_get_contents('php://input'), true);

        // Prepare and execute the SQL query
        $stmt = $pdo->prepare("INSERT INTO room (room) VALUES (:room) ");
        $stmt->bindParam(':room', $input['room']);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Room saved successfully!']);
        } else {
            echo json_encode(['message' => 'Failed to save room.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
    }
?>