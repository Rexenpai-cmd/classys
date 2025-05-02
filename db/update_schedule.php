
<?php
// update_schedule.php

// Include database connection
// Database connection parameters
$host = 'localhost';
$username = 'root'; // Add your username here
$password = ''; // Add your password here
$dbname = 'newclass';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize response array
$response = array();

// Check if all required fields are set
if (
    isset($_POST['schedule_id']) && 
    isset($_POST['class_id']) && 
    isset($_POST['instructor_id']) && 
    isset($_POST['subject_id']) && 
    isset($_POST['room_id']) && 
    isset($_POST['time_start']) && 
    isset($_POST['time_end']) && 
    isset($_POST['days'])
) {
    // Get form data
    $schedule_id = $_POST['schedule_id'];
    $class_id = $_POST['class_id'];
    $instructor_id = $_POST['instructor_id'];
    $subject_id = $_POST['subject_id'];
    $room_id = $_POST['room_id'];
    $time_start = $_POST['time_start'];
    $time_end = $_POST['time_end'];
    $days = $_POST['days'];
    
    // Update the schedule in the database
    $query = "UPDATE schedules SET 
                class = ?, 
                instructor = ?, 
                subject = ?, 
                room = ?, 
                timeStart = ?, 
                timeEnd = ?, 
                days = ? 
              WHERE id = ?";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiiisssi", $class_id, $instructor_id, $subject_id, $room_id, $time_start, $time_end, $days, $schedule_id);
    
    if ($stmt->execute()) {
        // Return success response
        echo json_encode(['status' => 'success', 'message' => 'Schedule updated successfully']);
    } else {
        // Return error response
        echo json_encode(['status' => 'error', 'message' => 'Failed to update schedule: ' . $conn->error]);
    }
    
    $stmt->close();
} else {
    // Return error if required fields are missing
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
}

$conn->close();
?>