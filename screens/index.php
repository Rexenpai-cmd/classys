<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "newclass");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch 5 newest users based on the timestamp
$sql = "SELECT name, timestamp, photo FROM users ORDER BY timestamp DESC LIMIT 5";
$result = $conn->query($sql);

// Fetch the 5 newest schedules with joins
$schedule_sql = "
    SELECT 
        s.timestamp,
        CONCAT(u.name, ' ', u.lname) AS instructor_name,
        CONCAT(c.course, ' ', c.year, c.section) AS class_name,
        r.room,
        subj.subject_title
    FROM schedules s
    LEFT JOIN users u ON s.instructor = u.id
    LEFT JOIN class c ON s.class = c.id
    LEFT JOIN room r ON s.room = r.id
    LEFT JOIN subjects subj ON s.subject = subj.id
    ORDER BY s.timestamp DESC
    LIMIT 5
";
$schedule_result = $conn->query($schedule_sql);

function time_ago($timestamp) {
    $time_ago = strtotime($timestamp);
    $current_time = time();
    $time_diff = $current_time - $time_ago;
    $seconds = $time_diff;
    $minutes      = round($seconds / 60);           // value 60 is seconds
    $hours        = round($seconds / 3600);         // value 3600 is 60 minutes * 60 sec
    $days         = round($seconds / 86400);        // value 86400 is 24 hours * 60 minutes * 60 sec
    $weeks        = round($seconds / 604800);       // value 604800 is 7 days * 24 hours * 60 minutes * 60 sec
    $months       = round($seconds / 2629440);      // value 2629440 is ((365+365+365+365)/4/12) days * 24 hours * 60 minutes * 60 sec
    $years        = round($seconds / 31553280);     // value 31553280 is ((365+365+365+365)/4) days * 24 hours * 60 minutes * 60 sec

    if ($seconds <= 60) {
        return "Just Now";
    } else if ($minutes <= 60) {
        if ($minutes == 1) {
            return "one minute ago";
        } else {
            return "$minutes minutes ago";
        }
    } else if ($hours <= 24) {
        if ($hours == 1) {
            return "an hour ago";
        } else {
            return "$hours hours ago";
        }
    } else if ($days <= 7) {
        if ($days == 1) {
            return "yesterday";
        } else {
            return "$days days ago";
        }
    } else if ($weeks <= 4.3) { // 4.3 == 30/7
        if ($weeks == 1) {
            return "one week ago";
        } else {
            return "$weeks weeks ago";
        }
    } else if ($months <= 12) {
        if ($months == 1) {
            return "one month ago";
        } else {
            return "$months months ago";
        }
    } else {
        if ($years == 1) {
            return "one year ago";
        } else {
            return "$years years ago";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="../styles/index.css">
    <script src="https://kit.fontawesome.com/8cb11b4552.js" crossorigin="anonymous"></script>
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
                    <?php
                    // Display the 5 newest users
                    while ($row = $result->fetch_assoc()) {
                        $name = htmlspecialchars($row['name']);
                        $timestamp = $row['timestamp'];
                        $photo = $row['photo'] ? $row['photo'] : '../images/logo.png'; // Use default photo if null
                        $timeAgo = time_ago($timestamp);
                        ?>
                        <div class="user">
                            <img src="<?php echo $photo; ?>" alt="Profile Photo">
                            <h3><?php echo $name; ?></h3>
                            <p><?php echo $timeAgo; ?></p>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="user">
                        <a href="instructors.php">
                            <i class="fa-solid fa-plus"></i>
                            <p>See More</p>
                        </a>
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
                            <th>Room</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $schedule_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['instructor_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['subject_title']); ?></td>
                                <td><?php echo htmlspecialchars($row['room']); ?></td>
                                <td><?php echo time_ago($row['timestamp']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <a href="schedules.php">Show All</a>
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
