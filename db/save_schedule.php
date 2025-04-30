<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'newclass';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit; // Exit to prevent further execution
}

// Day mapping for full names
$day_full_names = [
    'M' => 'Monday',
    'T' => 'Tuesday',
    'W' => 'Wednesday',
    'Th' => 'Thursday',
    'F' => 'Friday',
    'S' => 'Saturday',
    'Su' => 'Sunday'
];

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and retrieve the posted data
    $class_id = $_POST['class_id'];
    $instructor_id = $_POST['instructor_id'];
    $subject_id = $_POST['subject_id'];
    $room_id = $_POST['room_id'];
    $time_start = $_POST['time_start'];
    $time_end = $_POST['time_end'];
    $days = isset($_POST['days']) ? explode(',', $_POST['days']) : [];  // Ensure it's an array

    // Prepare conditions for checking conflicts
    $dayConditions = array_map(function($day) {
        return "FIND_IN_SET('$day', days)";
    }, $days);
    $dayCondition = implode(' OR ', $dayConditions);

    // Fetch class name
    $sql_class_name = "SELECT CONCAT(course, ' ', year, section) AS class_name FROM class WHERE id = ?";
    $stmt_class_name = $conn->prepare($sql_class_name);
    $stmt_class_name->bind_param("i", $class_id);
    $stmt_class_name->execute();
    $class_result = $stmt_class_name->get_result();
    $class_name = $class_result->fetch_assoc()['class_name'];

    // Fetch room name
    $sql_room_name = "SELECT room FROM room WHERE id = ?";
    $stmt_room_name = $conn->prepare($sql_room_name);
    $stmt_room_name->bind_param("i", $room_id);
    $stmt_room_name->execute();
    $room_result = $stmt_room_name->get_result();
    $room_name = $room_result->fetch_assoc()['room'];

    // Fetch instructor name
    $sql_instructor_name = "SELECT CONCAT(name, ' ', lname) AS instructor_name FROM users WHERE id = ?";
    $stmt_instructor_name = $conn->prepare($sql_instructor_name);
    $stmt_instructor_name->bind_param("i", $instructor_id);
    $stmt_instructor_name->execute();
    $instructor_result = $stmt_instructor_name->get_result();
    $instructor_name = $instructor_result->fetch_assoc()['instructor_name'];

    // Check for conflicts for the class
    $sql_check_class = "SELECT days FROM schedules WHERE class = ? AND ($dayCondition) AND (
        (timeStart < ? AND timeEnd > ?) OR
        (timeStart < ? AND timeEnd > ?)
    )";

    // Check for conflicts for the instructor
    $sql_check_instructor = "SELECT days FROM schedules WHERE instructor = ? AND ($dayCondition) AND (
        (timeStart < ? AND timeEnd > ?) OR
        (timeStart < ? AND timeEnd > ?)
    )";

    // Check for conflicts for the room
    $sql_check_room = "SELECT days FROM schedules WHERE room = ? AND ($dayCondition) AND (
        (timeStart < ? AND timeEnd > ?) OR
        (timeStart < ? AND timeEnd > ?)
    )";

    // Execute the checks
    $conflict_found = false;

    // Check room conflicts
    if ($stmt = $conn->prepare($sql_check_room)) {
        $stmt->bind_param("sssss", $room_id, $time_end, $time_start, $time_start, $time_end);
        $stmt->execute();
        $result_room = $stmt->get_result();
        if ($result_room->num_rows > 0) {
            $conflicting_days = [];
            while ($row = $result_room->fetch_assoc()) {
                $existing_days = explode(',', $row['days']);
                foreach ($days as $day) {
                    if (in_array($day, $existing_days)) {
                        $conflicting_days[] = $day_full_names[$day];
                    }
                }
            }
            $conflicting_days = array_unique($conflicting_days);
            $response = [
                'success' => false,
                'message' => "Room {$room_name} is not available from {$time_start} to {$time_end} on " . implode(', ', $conflicting_days) . "."
            ];
            $conflict_found = true;
        }
    }

    // Check instructor conflicts
    if (!$conflict_found && $stmt = $conn->prepare($sql_check_instructor)) {
        $stmt->bind_param("sssss", $instructor_id, $time_end, $time_start, $time_start, $time_end);
        $stmt->execute();
        $result_instructor = $stmt->get_result();
        if ($result_instructor->num_rows > 0) {
            $conflicting_days = [];
            while ($row = $result_instructor->fetch_assoc()) {
                $existing_days = explode(',', $row['days']);
                foreach ($days as $day) {
                    if (in_array($day, $existing_days)) {
                        $conflicting_days[] = $day_full_names[$day];
                    }
                }
            }
            $conflicting_days = array_unique($conflicting_days);
            $response = [
                'success' => false,
                'message' => "Instructor {$instructor_name} is not available from {$time_start} to {$time_end} on " . implode(', ', $conflicting_days) . "."
            ];
            $conflict_found = true;
        }
    }

    // Check class conflicts
    if (!$conflict_found && $stmt = $conn->prepare($sql_check_class)) {
        $stmt->bind_param("sssss", $class_id, $time_end, $time_start, $time_start, $time_end);
        $stmt->execute();
        $result_class = $stmt->get_result();
        if ($result_class->num_rows > 0) {
            $conflicting_days = [];
            while ($row = $result_class->fetch_assoc()) {
                $existing_days = explode(',', $row['days']);
                foreach ($days as $day) {
                    if (in_array($day, $existing_days)) {
                        $conflicting_days[] = $day_full_names[$day];
                    }
                }
            }
            $conflicting_days = array_unique($conflicting_days);
            $response = [
                'success' => false,
                'message' => "Class {$class_name} is not available from {$time_start} to {$time_end} on " . implode(', ', $conflicting_days) . "."
            ];
            $conflict_found = true;
        }
    }

    // If no conflicts were found, insert the new schedule along with current timestamp
    if (!$conflict_found) {
        $current_timestamp = date('Y-m-d H:i:s'); // Current timestamp

        $sql_insert = "INSERT INTO schedules (class, instructor, subject, room, timeStart, timeEnd, days, timestamp)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        if ($insert_stmt = $conn->prepare($sql_insert)) {
            // Convert days array into a comma-separated string
            $days_str = implode(',', $days); // Ensure this is an array

            // Correct binding of parameters: 4 integers, 3 strings, and 1 datetime
            $insert_stmt->bind_param("iiisssss", $class_id, $instructor_id, $subject_id, $room_id, $time_start, $time_end, $days_str, $current_timestamp);

            if ($insert_stmt->execute()) {
                $response = ['success' => true, 'message' => 'Schedule saved successfully!'];
            } else {
                $response = ['success' => false, 'message' => 'Failed to save the schedule. Please try again.'];
            }
            $insert_stmt->close();
        } else {
            $response = ['success' => false, 'message' => 'Failed to prepare the insert query.'];
        }
    }

    // Close statements
    $stmt->close();
    $conn->close();

    // Return the response as JSON
    echo json_encode($response);
    exit; // Ensure no further output
}
?>
