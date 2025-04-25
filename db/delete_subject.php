<?php
// Database connection details
$host = 'localhost'; // or your database host
$db = 'newclass'; // your database name
$user = 'root'; // your database username
$pass = ''; // your database password

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the subject ID from the URL
    if (isset($_GET['id'])) {
        $subjectId = intval($_GET['id']); // Ensure it's an integer

        // Prepare the DELETE statement
        $stmt = $pdo->prepare("DELETE FROM subject WHERE id = :id");
        $stmt->bindParam(':id', $subjectId, PDO::PARAM_INT);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Subject deleted successfully.";
        } else {
            echo "Error deleting subject.";
        }
    } else {
        echo "No subject ID provided.";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>