<?php
// edit_class.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['classId'];
    $course = $_POST['course'];
    $year = $_POST['year'];
    $section = $_POST['section'];

    // DB config
    $host = 'localhost';
    $db = 'newclass';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("UPDATE class SET course = ?, year = ?, section = ? WHERE id = ?");
        $stmt->execute([$course, $year, $section, $id]);

        echo json_encode(['success' => true, 'message' => 'Class updated successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
