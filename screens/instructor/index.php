<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit;
}

$instructorId = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "newclass");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all schedules for this instructor with joined data
$sql = "
    SELECT 
        CONCAT(c.course, ' ', c.year, c.section) AS class_name,
        CONCAT(u.name, ' ', u.lname) AS instructor_name,
        subj.subject_title,
        r.room,
        s.timeStart,
        s.timeEnd,
        s.days
    FROM schedules s
    LEFT JOIN class c ON s.class = c.id
    LEFT JOIN users u ON s.instructor = u.id
    LEFT JOIN subjects subj ON s.subject = subj.id
    LEFT JOIN room r ON s.room = r.id
    WHERE s.instructor = ?
    ORDER BY s.timeStart ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $instructorId);
$stmt->execute();
$result = $stmt->get_result();
?>

<head>
    <link rel="stylesheet" href="./index.css">
</head>

<body>
    <?php include './components/topbar.php'; ?>

    <div class="container">
        <?php include './components/sidebar.php'; ?>

        <div class="schedules-container">
            <div class="header">
                <h2>My Schedules</h2>
                <div class="actions-container">
                    <!-- Add buttons or filters here if needed -->
                </div>
            </div>

            <table class="schedule-table">
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Instructor</th>
                        <th>Subject</th>
                        <th>Room</th>
                        <th>Time</th>
                        <th>Days</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['instructor_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['subject_title']); ?></td>
                                <td><?php echo htmlspecialchars($row['room']); ?></td>
                                <td>
                                    <?php
                                        echo date("h:i A", strtotime($row['timeStart'])) . " - " . 
                                             date("h:i A", strtotime($row['timeEnd']));
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        // Format days like: M,T,F ➝ Mon, Tue, Fri
                                        $dayMap = ['M' => 'Mon', 'T' => 'Tue', 'W' => 'Wed', 'Th' => 'Thu', 'F' => 'Fri', 'S' => 'Sat', 'Su' => 'Sun'];
                                        $dayCodes = explode(",", $row['days']);
                                        $dayLabels = array_map(function($d) use ($dayMap) {
                                            return $dayMap[trim($d)] ?? $d;
                                        }, $dayCodes);
                                        echo implode(", ", $dayLabels);
                                    ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align:center;">No schedules available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="../../index.js"></script>
    <script src="../../orders.js"></script>
</body>
