<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'newclass';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch and store results into arrays (so they can be reused)
$classes = [];
$result_classes = $conn->query("SELECT id, CONCAT(course, ' ', year, section) AS class_name FROM class");
while ($row = $result_classes->fetch_assoc()) {
    $classes[] = $row;
}

$instructors = [];
$result_instructors = $conn->query("SELECT id, CONCAT(name, ' ', lname) AS instructor_name FROM users");
while ($row = $result_instructors->fetch_assoc()) {
    $instructors[] = $row;
}

$subjects = [];
$result_subjects = $conn->query("SELECT id, subject_title, year FROM subjects");
while ($row = $result_subjects->fetch_assoc()) {
    $subjects[] = $row;
}

$rooms = [];
$result_rooms = $conn->query("SELECT id, room FROM room");
while ($row = $result_rooms->fetch_assoc()) {
    $rooms[] = $row;
}
?>

<head>
    <link rel="stylesheet" href="../styles/schedules.css">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://kit.fontawesome.com/8cb11b4552.js" crossorigin="anonymous"></script>
</head>

<body>

    <!-- Sidebar Section -->
    <?php include '../components/topbar.php'; ?>
    <!-- End of Sidebar Section -->

    <div class="alert-container">
        <div class="message">
            <p id="responseMsg"></p>
        </div>
    </div>

    <div class="container">
        <!-- Sidebar Section -->
        <?php include '../components/sidebar.php'; ?>
        <!-- End of Sidebar Section -->


        <div class="outer-container">
            <!-- Main Content -->
            <div class="schedules-container">
                <div class="header">
                    <h2>Created Schedules</h2>
                    <div class="actions-container">
                        <button id="addScheduleBtn"><i class="fa-solid fa-plus"></i>Add Schedule</button>
                    </div>
                </div>
                <div class="table-container">
                    <table class="schedule-table">
                        <thead>
                            <tr>
                                <th>Class</th>
                                <th>Instructor</th>
                                <th>Subject</th>
                                <th>Room</th>
                                <th>Time</th>
                                <th>Days</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add Schedule Modal -->
            <div id="scheduleModal" class="modal">
                <div class="modal-content">
                    <p>Add Schedule</p>
                    <form>
                        <div class="floating-label">
                            <input type="text" id="class" placeholder=" " required onclick="toggleMenu('class-menu')" autocomplete="off"/>
                            <label for="class">Select Class</label>
                            <div class="menu-container" id="class-menu">
                                <?php
                                    if (!empty($classes)) {
                                        foreach ($classes as $row) {
                                            echo "<div class='menu-item' onclick='selectItem(\"class\", \"{$row['class_name']}\", {$row['id']})'>{$row['class_name']}</div>";
                                        }
                                    } else {
                                        echo "<div class='menu-item'>No classes available</div>";
                                    }
                                ?>
                            </div>
                        </div>

                        <div class="floating-label">
                            <input type="text" id="instructor" placeholder=" " required onclick="toggleMenu('instructor-menu')" autocomplete="off"/>
                            <label for="instructor">Select Instructor</label>
                            <div class="menu-container" id="instructor-menu">
                                <?php
                                    if (!empty($instructors)) {
                                        foreach ($instructors as $row) {
                                            echo "<div class='menu-item' onclick='selectItem(\"instructor\", \"{$row['instructor_name']}\", {$row['id']})'>{$row['instructor_name']}</div>";
                                        }
                                    } else {
                                        echo "<div class='menu-item'>No classes available</div>";
                                    }
                                ?>
                            </div>
                        </div>

                        <div class="floating-label">
                            <input type="text" id="subject" placeholder=" " required onclick="toggleMenu('subject-menu')" autocomplete="off"/>
                            <label for="subject">Select Subject</label>
                            <div class="menu-container" id="subject-menu">
                                <?php
                                    if (!empty($subjects)) {
                                        foreach ($subjects as $row) {
                                            echo "<div class='menu-item' data-year='{$row['year']}' onclick='selectItem(\"subject\", \"{$row['subject_title']}\", {$row['id']})'>{$row['subject_title']}</div>";
                                        }
                                    } else {
                                        echo "<div class='menu-item'>No subjects available</div>";
                                    }
                                ?>
                            </div>
                        </div>

                        <div class="floating-label">
                            <input type="text" id="room" placeholder=" " required onclick="toggleMenu('room-menu')" autocomplete="off"/>
                            <label for="room">Select Room</label>
                            <div class="menu-container" id="room-menu">
                                <?php
                                    if (!empty($rooms)) {
                                        foreach ($rooms as $row) {
                                            echo "<div class='menu-item' onclick='selectItem(\"room\", \"{$row['room']}\", {$row['id']})'>{$row['room']}</div>";
                                        }
                                    } else {
                                        echo "<div class='menu-item'>No rooms available</div>";
                                    }
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="floating-label">
                                <input type="time" id="timeStart" required autocomplete="off"/>
                                <label for="timeStart">Time Start</label>
                            </div>
                            <div class="floating-label">
                                <input type="time" id="timeEnd" required autocomplete="off"/>
                                <label for="timeEnd">Time End</label>
                            </div>
                        </div>

                        <div class="days-container">
                            <p>Select Days</p>
                            <div class="days-row">
                                <label>
                                    <input type="checkbox" name="days" value="Su" />
                                    <span>Sun</span>
                                </label>
                                <label>
                                    <input type="checkbox" name="days" value="M" />
                                    <span>Mon</span>
                                </label>
                                <label>
                                    <input type="checkbox" name="days" value="T" />
                                    <span>Tue</span>
                                </label>
                                <label>
                                    <input type="checkbox" name="days" value="W" />
                                    <span>Wed</span>
                                </label>
                                <label>
                                    <input type="checkbox" name="days" value="Th" />
                                    <span>Thu</span>
                                </label>
                                <label>
                                    <input type="checkbox" name="days" value="F" />
                                    <span>Fri</span>
                                </label>
                                <label>
                                    <input type="checkbox" name="days" value="S" />
                                    <span>Sat</span>
                                </label>
                            </div>
                        </div>
                        <div class="modal-buttons">
                            <button id="closeModal">Cancel</button>
                            <button type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>

<!-- Edit Schedule Modal -->
<div id="editScheduleModal" class="modal">
    <div class="modal-content">
        <p>Edit Schedule</p>
        <form>
            <!-- Hidden inputs for IDs -->
            <input type="hidden" name="schedule_id" value="">
            <input type="hidden" name="class_id" value="">
            <input type="hidden" name="instructor_id" value="">
            <input type="hidden" name="subject_id" value="">
            <input type="hidden" name="room_id" value="">
            
            <div class="floating-label">
                <input type="text" id="editClass" placeholder=" " required onclick="toggleMenu('edit-class-menu')" autocomplete="off"/>
                <label for="editClass">Select Class</label>
                <div class="menu-container" id="edit-class-menu">
                    <?php
                        if (!empty($classes)) {
                            foreach ($classes as $row) {
                                echo "<div class='menu-item' onclick='selectItem(\"editClass\", \"{$row['class_name']}\", {$row['id']})'>{$row['class_name']}</div>";
                            }
                        } else {
                            echo "<div class='menu-item'>No classes available</div>";
                        }
                    ?>
                </div>
            </div>

            <div class="floating-label">
                <input type="text" id="editInstructor" placeholder=" " required onclick="toggleMenu('edit-instructor-menu')" autocomplete="off"/>
                <label for="editInstructor">Select Instructor</label>
                <div class="menu-container" id="edit-instructor-menu">
                    <?php
                        if (!empty($instructors)) {
                            foreach ($instructors as $row) {
                                echo "<div class='menu-item' onclick='selectItem(\"editInstructor\", \"{$row['instructor_name']}\", {$row['id']})'>{$row['instructor_name']}</div>";
                            }
                        } else {
                            echo "<div class='menu-item'>No classes available</div>";
                        }
                    ?>
                </div>
            </div>

            <div class="floating-label">
                <input type="text" id="editSubject" placeholder=" " required onclick="toggleMenu('edit-subject-menu')" autocomplete="off"/>
                <label for="editSubject">Select Subject</label>
                <div class="menu-container" id="edit-subject-menu">
                    <?php
                        if (!empty($subjects)) {
                            foreach ($subjects as $row) {
                                echo "<div class='menu-item' data-year='{$row['year']}' onclick='selectItem(\"editSubject\", \"{$row['subject_title']}\", {$row['id']})'>{$row['subject_title']}</div>";
                            }
                        } else {
                            echo "<div class='menu-item'>No subjects available</div>";
                        }
                    ?>
                </div>
            </div>

            <div class="floating-label">
                <input type="text" id="editRoom" placeholder=" " required onclick="toggleMenu('edit-room-menu')" autocomplete="off"/>
                <label for="editRoom">Select Room</label>
                <div class="menu-container" id="edit-room-menu">
                    <?php
                        if (!empty($rooms)) {
                            foreach ($rooms as $row) {
                                echo "<div class='menu-item' onclick='selectItem(\"editRoom\", \"{$row['room']}\", {$row['id']})'>{$row['room']}</div>";
                            }
                        } else {
                            echo "<div class='menu-item'>No rooms available</div>";
                        }
                    ?>
                </div>
            </div>

            <div class="form-group">
                <div class="floating-label">
                    <input type="time" id="editTimeStart" required autocomplete="off"/>
                    <label for="editTimeStart">Time Start</label>
                </div>
                <div class="floating-label">
                    <input type="time" id="editTimeEnd" required autocomplete="off"/>
                    <label for="editTimeEnd">Time End</label>
                </div>
            </div>

            <div class="days-container">
                <p>Select Days</p>
                <div class="days-row">
                    <label>
                        <input type="checkbox" name="days" value="Su" />
                        <span>Sun</span>
                    </label>
                    <label>
                        <input type="checkbox" name="days" value="M" />
                        <span>Mon</span>
                    </label>
                    <label>
                        <input type="checkbox" name="days" value="T" />
                        <span>Tue</span>
                    </label>
                    <label>
                        <input type="checkbox" name="days" value="W" />
                        <span>Wed</span>
                    </label>
                    <label>
                        <input type="checkbox" name="days" value="Th" />
                        <span>Thu</span>
                    </label>
                    <label>
                        <input type="checkbox" name="days" value="F" />
                        <span>Fri</span>
                    </label>
                    <label>
                        <input type="checkbox" name="days" value="S" />
                        <span>Sat</span>
                    </label>
                </div>
            </div>
            <div class="modal-buttons">
                <button id="closeEditModal">Cancel</button>
                <button type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

            <!-- Delete Schedule Modal -->
            <div class="deleteModal" id="deleteModal">
                <div class="deleteModalContent">
                    <div class="icon">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <h2>Are You Sure?</h2>
                    <p>Are you sure you want to delete this subject?</p>
                    <p>This action cannot be undone</p>
                    <div class="deleteButtons">
                        <button id="confirmDelete">Delete Schedule</button>
                        <button id="cancelDelete">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../index.js"></script>
    <script src="../scripts/schedules.js"></script>
</body>

