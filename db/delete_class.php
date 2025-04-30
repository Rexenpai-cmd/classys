<?php
// delete_class.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the class ID from the POST data
    $data = json_decode(file_get_contents('php://input'), true);
    $classId = $data['id'];

    // DB config
    $host = 'localhost';
    $db = 'newclass';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the delete statement
        $stmt = $pdo->prepare("DELETE FROM class WHERE id = ?");
        $stmt->execute([$classId]);

        echo json_encode(['success' => true, 'message' => 'Class deleted successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
