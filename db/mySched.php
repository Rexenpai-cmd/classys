<?php
session_start();

// Check if the instructor_id is passed
if (!isset($_POST['instructor_id'])) {
    echo json_encode([]);
    exit();
}

// Get the instructor ID from the POST request
$instructor_id = $_POST['instructor_id'];

error_log("Instructor ID from session: " . $_SESSION['user_id']);

// Log the instructor_id to the PHP error log for debugging
error_log("Instructor ID from POST: " . $instructor_id); // Logs to PHP error log

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'newclass';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch the schedules for the logged-in instructor
$sql = "
    SELECT 
        schedules.id AS schedule_id,
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
    WHERE schedules.instructor = ?
";

// Prepare and execute the query
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $instructor_id); // Bind the instructor ID
$stmt->execute();
$result = $stmt->get_result();

$schedules = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $schedules[] = [
            'class_name' => $row['class_name'],
            'instructor_name' => $row['instructor_name'],
            'subject_title' => $row['subject_title'],
            'room' => $row['room'],
            'timeStart' => formatTime($row['timeStart']),
            'timeEnd' => formatTime($row['timeEnd']),
            'days' => formatDays($row['days']),
        ];
    }
}

// Return the schedule data as JSON
echo json_encode($schedules);

// Function to format time
function formatTime($time) {
    return date('g:i A', strtotime($time)); // Formats time like "8:30 AM"
}

// Function to format days
function formatDays($days) {
    return implode(" ", explode(",", $days)); // Converts comma separated days to space separated
}
?>
