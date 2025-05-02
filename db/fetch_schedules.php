<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$host = 'localhost'; // Replace with your host, usually localhost
$username = 'root';  // Replace with your database username
$password = '';      // Replace with your database password
$dbname = 'newclass'; // Replace with your database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch schedules with joined data from related tables
$sql = "
    SELECT 
        schedules.id AS schedule_id,
        class.id AS class_id,
        users.id AS instructor_id,
        subjects.id AS subject_id,
        room.id AS room_id,
        class.course, 
        class.year, 
        class.section,
        CONCAT(class.course, ' ', class.year, class.section) AS class_name,
        CONCAT(users.name, ' ', users.lname) AS instructor_name,
        subjects.subject_title,
        room.room,
        schedules.timeStart,
        schedules.timeEnd,
        schedules.days
    FROM schedules
    JOIN class ON schedules.class = class.id
    JOIN users ON schedules.instructor = users.id
    JOIN subjects ON schedules.subject = subjects.id
    JOIN room ON schedules.room = room.id
";

$result = $conn->query($sql);
$schedules = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $schedule = [
            'schedule_id' => $row['schedule_id'],
            'class_id' => $row['class_id'],
            'instructor_id' => $row['instructor_id'],
            'subject_id' => $row['subject_id'],
            'room_id' => $row['room_id'],
            'class_name' => $row['class_name'],
            'instructor_name' => $row['instructor_name'],
            'subject_title' => $row['subject_title'],
            'room' => $row['room'],
            'timeStart' => date('H:i', strtotime($row['timeStart'])),
            'timeEnd' => date('H:i', strtotime($row['timeEnd'])),
            'days' => $row['days']
        ];
        $schedules[] = $schedule;
    }
} 

// Return the data as JSON
echo json_encode($schedules);

$conn->close();
?>