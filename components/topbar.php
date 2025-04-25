<?php

$conn = new mysqli("localhost", "root", "", "newclass");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details from the database
$stmt = $conn->prepare("SELECT name, lname, email, password, status, photo FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($firstname, $lastname, $email, $password, $status, $photo);
$stmt->fetch();
$stmt->close();
$conn->close();

$role = ($status == 0) ? "Admin" : (($status == 1) ? "Instructor" : "Unknown");
?>

<head>
    <link rel="stylesheet" href="../styles/topbar.css">
</head>
<div class="nav">
    <div class="toggle">
        <button id="menu-btn">
            <span class="material-icons-sharp">
                menu
            </span>
        </button>
        <div class="logo">
            <img src="../images/logo.png" alt="Logo">
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
                <img src="../images/profile-1.jpg">
            </div>
        </div>
    </div>
</div>

