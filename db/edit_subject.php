<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request.";
    exit;
}

$conn = new mysqli("localhost", "root", "", "newclass");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_POST['editid'] ?? '';
$section = $_POST['editsection'] ?? '';
$code = $_POST['editsubject'] ?? '';
$title = $_POST['edittitle'] ?? '';
$year = $_POST['edityear'] ?? '';
$unit = $_POST['editunit'] ?? '';

if (empty($id)) {
    echo "Missing subject ID.";
    exit;
}

// Get current values
$sql = "SELECT * FROM subjects WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Subject not found.";
    exit;
}

$current = $result->fetch_assoc();

// Track changed fields
$updates = [];
$params = [];
$types = "";

if ($section !== $current['section_code']) {
    $updates[] = "section_code = ?";
    $params[] = $section;
    $types .= "s";
}
if ($code !== $current['subject_code']) {
    $updates[] = "subject_code = ?";
    $params[] = $code;
    $types .= "s";
}
if ($title !== $current['subject_title']) {
    $updates[] = "subject_title = ?";
    $params[] = $title;
    $types .= "s";
}
if ($year !== $current['year']) {
    $updates[] = "year = ?";
    $params[] = $year;
    $types .= "s";
}
if ($unit !== $current['unit']) {
    $updates[] = "unit = ?";
    $params[] = $unit;
    $types .= "s";
}

if (empty($updates)) {
    echo "No changes detected.";
    exit;
}

$types .= "i"; // for ID
$params[] = $id;

$updateSql = "UPDATE subjects SET " . implode(", ", $updates) . " WHERE id = ?";
$updateStmt = $conn->prepare($updateSql);
$updateStmt->bind_param($types, ...$params);

if ($updateStmt->execute()) {
    echo "Subject updated successfully!";
} else {
    echo "Error updating subject: " . $conn->error;
}

$conn->close();
?>
