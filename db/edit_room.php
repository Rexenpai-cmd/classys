<?php
// Initialize a new PDO connection
$host = 'localhost'; // Database host
$dbname = 'newclass'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

try {
    // Create a new PDO instance for the database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // If the connection fails, return an error response
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the data from the AJAX request
    $roomId = $_POST['id'];
    $roomName = $_POST['room'];

    // Ensure the input is not empty
    if (!empty($roomId) && !empty($roomName)) {
        // Prepare the SQL query to update the room name
        $query = "UPDATE room SET room = :room WHERE id = :id";

        try {
            // Prepare the query using PDO
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':room', $roomName);
            $stmt->bindParam(':id', $roomId);

            // Execute the query
            if ($stmt->execute()) {
                // Return a success response
                echo json_encode(['success' => true, 'message' => 'Room updated successfully!']);
            } else {
                // Return a failure response
                echo json_encode(['success' => false, 'message' => 'Failed to update room. Please try again.']);
            }
        } catch (Exception $e) {
            // Catch any exceptions and return an error message
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        // Return a failure response if data is invalid
        echo json_encode(['success' => false, 'message' => 'Invalid room data.']);
    }
} else {
    // If the request is not POST, return failure
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
