<?php

// Start the session if it's not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in by verifying if 'user_id' is set in the session
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
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
            <div class="profile-photo" id="profile-photo">
                <img src="../images/profile.png" alt="Profile Photo">
            </div>

            <!--

            <div class="profile-container" id="profile-container">
                <div class="item" id="update">
                    <p>Update Profile</p>
                </div>
                <div class="item" id="change">
                    <p>Change Password</p>
                </div>
            </div>

            <div class="update-container" id="update-container">
                <h3>Update Profile</h3>
                <form action="">
                    <div class="floating-label">
                        <input type="text" id="firstname" placeholder=" " autocomplete="off"/>
                        <label for="firstname">Firstname</label>
                    </div>
                    <div class="floating-label">
                        <input type="text" id="lastname" placeholder=" " autocomplete="off"/>
                        <label for="lastname">Lastname</label>
                    </div>
                    <div class="floating-label">
                        <input type="text" id="email" placeholder=" " autocomplete="off"/>
                        <label for="email">Email Address</label>
                    </div>
                    <div class="actions">
                        <button><p>Save</p></button>
                        <button><p>Cancel</p></button>
                    </div>
                </form>
            </div>

            <div class="change-container" id="change-container">
                <h3>Change Password</h3>
                <form action="">
                    <div class="floating-label">
                        <input type="text" id="verify" placeholder=" " autocomplete="off"/>
                        <label for="verify">Verify Password</label>
                    </div>
                    <div class="floating-label">
                        <input type="text" id="new" placeholder=" " autocomplete="off"/>
                        <label for="new">New Password</label>
                    </div>
                    <div class="floating-label">
                        <input type="text" id="confirm" placeholder=" " autocomplete="off"/>
                        <label for="confirm">Confirm New Password</label>
                    </div>
                    <div class="actions">
                        <button><p>Save</p></button>
                        <button><p>Cancel</p></button>
                    </div>
                </form>
            </div>

-->
        </div>
    </div>
</div>

<script>
/** 
 //   const profilePhoto = document.getElementById('profile-photo');
    const profileContainer = document.getElementById('profile-container');
    const updateContainer = document.getElementById('update-container');
    const changeContainer = document.getElementById('change-container');
    const update = document.getElementById('update');
    const change = document.getElementById('change');

    profilePhoto.addEventListener('click', () => {
        const isUpdateActive = updateContainer.classList.contains('active');
        const isChangeActive = changeContainer.classList.contains('active');

        if (isUpdateActive || isChangeActive) {
            updateContainer.classList.remove('active');
            changeContainer.classList.remove('active');
        } else {
            profileContainer.classList.toggle('active');
        }
    });

    update.addEventListener('click', () => {
        // Close profile container
        profileContainer.classList.remove('active');
        // Close change container if open
        changeContainer.classList.remove('active');
        // Toggle update container
        updateContainer.classList.toggle('active');
    });

    change.addEventListener('click', () => {
        // Close profile container
        profileContainer.classList.remove('active');
        // Close update container if open
        updateContainer.classList.remove('active');
        // Toggle change container
        changeContainer.classList.toggle('active');
    });
*/
</script>
