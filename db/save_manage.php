<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course = $_POST['course'];
    $year = $_POST['year'];
    $section = $_POST['section'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "newclass");

    if ($conn->connect_error) {
        http_response_code(500);
        echo "Connection failed: " . $conn->connect_error;
        exit;
    }

    // Prepare insert statement
    $stmt = $conn->prepare("INSERT INTO class (course, year, section) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $course, $year, $section);

    if ($stmt->execute()) {
        echo "Class added successfully!";
    } else {
        http_response_code(500);
        echo "Error saving subject.";
    }

    $stmt->close();
    $conn->close();
}
?>
