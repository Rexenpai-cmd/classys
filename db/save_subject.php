<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $section_code = $_POST['section_code'];
    $subject_code = $_POST['subject_code'];
    $subject_title = $_POST['subject_title'];
    $year = $_POST['year'];
    $unit = $_POST['unit'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "newclass");

    if ($conn->connect_error) {
        http_response_code(500);
        echo "Connection failed: " . $conn->connect_error;
        exit;
    }

    // Prepare insert statement
    $stmt = $conn->prepare("INSERT INTO subject (section_code, subject_code, subject_title, year, unit) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $section_code, $subject_code, $subject_title, $year, $unit);

    if ($stmt->execute()) {
        echo "Subject added successfully!";
    } else {
        http_response_code(500);
        echo "Error saving subject.";
    }

    $stmt->close();
    $conn->close();
}
?>
