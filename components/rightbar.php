        <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: ../index.php");
            exit;
        }

        $instructorId = $_SESSION['user_id'];

        $conn = new mysqli("localhost", "root", "", "newclass");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Map PHP day to shorthand
        $dayMap = [
            'Monday'    => 'M',
            'Tuesday'   => 'T',
            'Wednesday' => 'W',
            'Thursday'  => 'Th',
            'Friday'    => 'F',
            'Saturday'  => 'S',
            'Sunday'    => 'Su'
        ];

        $currentDay = $dayMap[date('l')];

        // Fetch today's schedules for the instructor, including room
        $sql = "
        SELECT 
            CONCAT(c.course, ' ', c.year, c.section) AS class_name,
            s.timeStart,
            s.timeEnd,
            r.room
        FROM schedules s
        LEFT JOIN class c ON s.class = c.id
        LEFT JOIN room r ON s.room = r.id
        WHERE s.instructor = ?
        AND FIND_IN_SET(?, s.days)
        ORDER BY s.timeStart ASC
    ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $instructorId, $currentDay);
        $stmt->execute();
        $result = $stmt->get_result();
        ?>
        
        <head>
            <link rel="stylesheet" href="../styles/rightbar.css">
        </head>
        <div class="right-section">
            <!-- End of Nav -->

            <div class="user-profile">
                <div class="logo">
                    <img src="../images/logo.png">
                    <h2>ClasSys</h2>
                    <p>Class Scheduling System</p>
                </div>
            </div>

            <div class="reminders">
                <div class="header">
                    <h2>Today's Schedules</h2>
                    <span class="material-icons-sharp">
                        notifications_none
                    </span>
                </div>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="notification">
                            <div class="icon">
                                <span class="material-icons-sharp">volume_up</span>
                            </div>
                            <div class="content">
                                <div class="info">
                                    <h3><?php echo htmlspecialchars($row['class_name']); ?></h3>
                                    <small class="text_muted">
                                        <?php 
                                            echo date("h:i A", strtotime($row['timeStart'])) . " - " . 
                                                date("h:i A", strtotime($row['timeEnd']));
                                        ?>
                                    </small>
                                </div>
                                <div class="room">
                                    <p>Room</p>
                                    <p><?php echo htmlspecialchars($row['room']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="padding: 1rem;">No schedules for today.</p>
                <?php endif; ?>
            </div>
        </div>