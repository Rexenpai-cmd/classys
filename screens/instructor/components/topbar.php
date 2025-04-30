<?php

// Start the session if it's not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in by verifying if 'user_id' is set in the session
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit;
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Establish database connection
$conn = new mysqli("localhost", "root", "", "newclass");

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details from the database using the user ID from the session
$stmt = $conn->prepare("SELECT name, lname, email, password, status, photo FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($firstname, $lastname, $email, $password, $status, $photo);
$stmt->fetch();
$stmt->close();
$conn->close();

// Determine the user's role based on the status
$role = ($status == 0) ? "Admin" : (($status == 1) ? "Instructor" : "Unknown");
?>

<head>
    <link rel="stylesheet" href="../../styles/topbar.css">
</head>
<div class="nav">
    <div class="toggle">
        <button id="menu-btn">
            <span class="material-icons-sharp">
                menu
            </span>
        </button>
        <div class="logo">
            <img src="../../images/logo.png" alt="Logo">
            <h2>Clas<span class="danger">Sys</span></h2>
        </div>
        <div class="close" id="close-btn">
            <span class="material-icons-sharp">close</span>
        </div>
    </div>

    <div class="actions-container">
        <div class="dark-mode">
            <span class="material-icons-sharp active">
                light_mode
            </span>
            <span class="material-icons-sharp">
                dark_mode
            </span>
        </div>

        <div class="profile">
            <div class="info">
                <p>Hey, <b><?php echo htmlspecialchars($firstname); ?></b></p>
                <small class="text-muted"><?php echo htmlspecialchars($role); ?></small>
            </div>
            <div class="profile-photo">
                <img src="../../images/profile-1.jpg">
            </div>
        </div>
    </div>
</div>

