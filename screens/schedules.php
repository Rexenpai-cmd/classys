<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}
?>

<head>
    <link rel="stylesheet" href="../styles/schedules.css">
    <script src="https://kit.fontawesome.com/8cb11b4552.js" crossorigin="anonymous"></script>
</head>

<body>

    <!-- Sidebar Section -->
    <?php include '../components/topbar.php'; ?>
    <!-- End of Sidebar Section -->

    <div class="container">
        <!-- Sidebar Section -->
        <?php include '../components/sidebar.php'; ?>
        <!-- End of Sidebar Section -->


        <div class="outer-container">
            <h2>Created Schedules</h2>
            <!-- Main Content -->
            <div class="schedules-container">
                <div class="header">
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
                            <tr>
                                <td>Math 101</td>
                                <td>John Doe</td>
                                <td>Mathematics</td>
                                <td>Room A</td>
                                <td>10:00 AM - 11:00 AM</td>
                                <td>Mon, Wed, Fri</td>
                                <td><button>Edit</button> <button>Delete</button></td>
                            </tr>
                            <tr>
                                <td>Eng 202</td>
                                <td>Jane Smith</td>
                                <td>English Literature</td>
                                <td>Room B</td>
                                <td>11:00 AM - 12:30 PM</td>
                                <td>Tue, Thu</td>
                                <td><button>Edit</button> <button>Delete</button></td>
                            </tr>
                            <tr>
                                <td>CS 303</td>
                                <td>Mike Brown</td>
                                <td>Computer Science</td>
                                <td>Room C</td>
                                <td>1:00 PM - 2:30 PM</td>
                                <td>Mon, Wed</td>
                                <td><button>Edit</button> <button>Delete</button></td>
                            </tr>
                            <tr>
                                <td>CS 303</td>
                                <td>Mike Brown</td>
                                <td>Computer Science</td>
                                <td>Room C</td>
                                <td>1:00 PM - 2:30 PM</td>
                                <td>Mon, Wed</td>
                                <td><button>Edit</button> <button>Delete</button></td>
                            </tr>
                            <tr>
                                <td>CS 303</td>
                                <td>Mike Brown</td>
                                <td>Computer Science</td>
                                <td>Room C</td>
                                <td>1:00 PM - 2:30 PM</td>
                                <td>Mon, Wed</td>
                                <td><button>Edit</button> <button>Delete</button></td>
                            </tr>
                            <tr>
                                <td>CS 303</td>
                                <td>Mike Brown</td>
                                <td>Computer Science</td>
                                <td>Room C</td>
                                <td>1:00 PM - 2:30 PM</td>
                                <td>Mon, Wed</td>
                                <td><button>Edit</button> <button>Delete</button></td>
                            </tr>
                            <tr>
                                <td>CS 303</td>
                                <td>Mike Brown</td>
                                <td>Computer Science</td>
                                <td>Room C</td>
                                <td>1:00 PM - 2:30 PM</td>
                                <td>Mon, Wed</td>
                                <td><button>Edit</button> <button>Delete</button></td>
                            </tr>
                            <tr>
                                <td>CS 303</td>
                                <td>Mike Brown</td>
                                <td>Computer Science</td>
                                <td>Room C</td>
                                <td>1:00 PM - 2:30 PM</td>
                                <td>Mon, Wed</td>
                                <td><button>Edit</button> <button>Delete</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal -->
            <div id="scheduleModal" class="modal">
                <div class="modal-content">
                    <p>Add Schedule</p>
                    <form>
                        
                        <div class="floating-label">
                            <input type="text" id="class" placeholder=" " required onclick="toggleMenu('class-menu')" autocomplete="off"/>
                            <label for="class">Select Class</label>
                            <div class="menu-container" id="class-menu">
                                <div class="menu-item" onclick="selectItem('class', 'Class 1')">Class 1</div>
                                <div class="menu-item" onclick="selectItem('class', 'Class 2')">Class 2</div>
                                <div class="menu-item" onclick="selectItem('class', 'Class 3')">Class 3</div>
                                <div class="menu-item" onclick="selectItem('class', 'Class 4')">Class 4</div>
                            </div>
                        </div>

                        <div class="floating-label">
                            <input type="text" id="instructor" placeholder=" " required onclick="toggleMenu('instructor-menu')" autocomplete="off"/>
                            <label for="instructor">Select Instructor</label>
                            <div class="menu-container" id="instructor-menu">
                                <div class="menu-item" onclick="selectItem('instructor', 'Instructor A')">Instructor A</div>
                                <div class="menu-item" onclick="selectItem('instructor', 'Instructor B')">Instructor B</div>
                                <div class="menu-item" onclick="selectItem('instructor', 'Instructor C')">Instructor C</div>
                            </div>
                        </div>

                        <div class="floating-label">
                            <input type="text" id="subject" placeholder=" " required onclick="toggleMenu('subject-menu')" autocomplete="off"/>
                            <label for="subject">Select Subject</label>
                            <div class="menu-container" id="subject-menu">
                                <div class="menu-item" onclick="selectItem('subject', 'Math')">Math</div>
                                <div class="menu-item" onclick="selectItem('subject', 'Science')">Science</div>
                                <div class="menu-item" onclick="selectItem('subject', 'History')">History</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="floating-label">
                                <input type="text" id="name" placeholder=" " required autocomplete="off"/>
                                <label for="name">Time Start</label>
                            </div>
                            <div class="floating-label">
                                <input type="text" id="name" placeholder=" " required autocomplete="off"/>
                                <label for="name">Time End</label>
                            </div>
                        </div>
                        <div class="days-container">
                            <p>Select Days</p>
                            <div class="days-row">
                                <label>
                                    <input type="checkbox" name="days" value="sun" />
                                    <span>Sun</span>
                                </label>
                                <label>
                                    <input type="checkbox" name="days" value="sun" />
                                    <span>Mon</span>
                                </label>
                                <label>
                                    <input type="checkbox" name="days" value="sun" />
                                    <span>Tue</span>
                                </label>
                                <label>
                                    <input type="checkbox" name="days" value="sun" />
                                    <span>Wed</span>
                                </label>
                                <label>
                                    <input type="checkbox" name="days" value="sun" />
                                    <span>Thu</span>
                                </label>
                                <label>
                                    <input type="checkbox" name="days" value="sun" />
                                    <span>Fri</span>
                                </label>
                                <label>
                                    <input type="checkbox" name="days" value="sun" />
                                    <span>Sat</span>
                                </label>
                            </div>
                        </div>
                        <div class="modal-buttons">
                            <button id="closeModal">Cancel</button>
                            <button>Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../index.js"></script>
    <script src="../scripts/schedules.js"></script>
</body>

