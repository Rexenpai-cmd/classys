<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="../styles/index.css">
    <title>Classys</title>
</head>

<body>

    <!-- Topbar Section -->
    <?php include '../components/topbar.php'; ?>
    <!-- End of Topbar Section -->

    <div class="container">
        <!-- Sidebar Section -->
        <?php include '../components/sidebar.php'; ?>
        <!-- End of Sidebar Section -->

        <!-- Main Content -->
        <main>
            <!-- New Users Section -->
            <div class="new-users">
                <h2>New Registered Instructors</h2>
                <div class="user-list">
                    <div class="user">
                        <img src="../images/profile-2.jpg">
                        <h3>Jack</h3>
                        <p>54 Min Ago</p>
                    </div>
                    <div class="user">
                        <img src="../images/profile-3.jpg">
                        <h3>Amir</h3>
                        <p>3 Hours Ago</p>
                    </div>
                    <div class="user">
                        <img src="../images/profile-4.jpg">
                        <h3>Ember</h3>
                        <p>6 Hours Ago</p>
                    </div>
                    <div class="user">
                        <img src="../images/plus.png">
                        <h3>More</h3>
                        <p>New User</p>
                    </div>
                    <div class="user">
                        <img src="../images/plus.png">
                        <h3>More</h3>
                        <p>New User</p>
                    </div>
                    <div class="user">
                        <img src="../images/plus.png">
                        <h3>More</h3>
                        <p>New User</p>
                    </div>
                </div>
            </div>
            <!-- End of New Users Section -->

            <!-- Recent Orders Table -->
            <div class="recent-orders">
                <h2>Recent Created Schedules</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Instructor</th>
                            <th>Subject</th>
                            <th>Time</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <a href="#">Show All</a>
            </div>
            <!-- End of Recent Orders -->

        </main>
        <!-- End of Main Content -->

        <!-- Right Section -->
            <?php include '../components/rightbar.php'; ?>
        <!-- End of Right Section -->
    </div>

    <script src="../index.js"></script>
    <script src="../orders.js"></script>
</body>

</html>