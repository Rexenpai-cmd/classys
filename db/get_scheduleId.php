<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'newclass';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

if (isset($_GET['id'])) {
    $schedule_id = $_GET['id'];

    $sql = "
    SELECT 
        s.id AS schedule_id,
        CONCAT(c.course, ' ', c.year, c.section) AS class_name,
        CONCAT(u.name, ' ', u.lname) AS instructor_name,
        sub.subject_title,
        sub.year AS subject_year,
        r.room,
        s.time_start,
        s.time_end,
        s.days
    FROM schedules s
    JOIN class c ON s.class_id = c.id
    JOIN users u ON s.instructor_id = u.id
    JOIN subjects sub ON s.subject_id = sub.id
    JOIN room r ON s.room_id = r.id
    WHERE s.id = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['error' => 'Query preparation failed: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("i", $schedule_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $schedule = $result->fetch_assoc();
        echo json_encode($schedule); // Proper JSON response
    } else {
        echo json_encode(['error' => 'Schedule not found']);
    }
} else {
    echo json_encode(['error' => 'Schedule ID is missing']);
}

$conn->close();
?>
