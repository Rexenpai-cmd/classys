<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure the user is logged in (adjust as necessary)
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "newclass"); // Replace with your DB details

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch instructors from the users table
$sql = "SELECT name, lname, email, status, photo FROM users WHERE status IN (0, 1)";
$result = $conn->query($sql);
?>

<head>
    <link rel="stylesheet" href="../styles/instructors.css">
</head>

<body>

    <!-- Sidebar Section -->
    <?php include '../components/topbar.php'; ?>
    <!-- End of Sidebar Section -->

    <div class="container">
        <!-- Sidebar Section -->
        <?php include '../components/sidebar.php'; ?>
        <!-- End of Sidebar Section -->

        <!-- Main Content -->
        <div class="instructors-container">
            <div class="header">
                <h2>Registered Instructors</h2>
                <div class="actions-container">
                    <!-- You can add action buttons here if needed -->
                </div>
            </div>
            <table class="instructor-table">
                <thead>
                    <tr> 
                        <th></th>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Email</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        // Output data for each row
                        while ($row = $result->fetch_assoc()) {
                            $statusText = $row['status'] == 0 ? 'Admin' : 'Instructor'; // Map status
                            $photo = !empty($row['photo']) ? $row['photo'] : '../images/logo.png'; // Use logo if photo is empty
                            echo "<tr>
                                <td><img src='{$photo}' alt='' class='instructor-profile'></td>
                                <td>{$row['name']}</td>
                                <td>{$row['lname']}</td>
                                <td>{$row['email']}</td>
                                <td>{$statusText}</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No instructors found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="../index.js"></script>
    <script src="../orders.js"></script>
</body>