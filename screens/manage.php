<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

// Database configuration
$host = 'localhost';
$db = 'newclass';
$user = 'root';
$pass = '';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch classes from the database
    $stmt = $pdo->prepare("SELECT course, year, section FROM class");
    $stmt->execute();
    $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <script src="https://kit.fontawesome.com/8cb11b4552.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../styles/manage.css">
    <title>Classys</title>
</head>

<body>
    <!-- Sidebar Section -->
        <?php include '../components/topbar.php'; ?>
    <!-- End of Sidebar Section -->

    <div class="container">
        <div class="alert-container">
            <div class="message">
                <p id="responseMsg"></p>
            </div>
        </div>
        <!-- Sidebar Section -->
        <?php include '../components/sidebar.php'; ?>
        <!-- End of Sidebar Section -->

        <!-- Main Content -->
        <div class="main-content">
            <div class="main-content-container">
                <div class="header">
                    <h2>Manage Class</h2>
                    <button id="addClass">
                        <i class="fa-solid fa-plus"></i>
                        Add Class
                    </button>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Year</th>
                                <th>Section</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($classes as $class): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($class['course']); ?></td>
                                    <td><?php echo htmlspecialchars($class['year']); ?></td>
                                    <td><?php echo htmlspecialchars($class['section']); ?></td>
                                    <td>
                                        <button id="editClass" class="edit-button">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button id="deleteClass" class="delete-button">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="main-content-container">
                <div class="header">
                    <h2>Manage Room</h2>
                    <button id="addRoom">
                        <i class="fa-solid fa-plus"></i>
                        Add Room
                    </button>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Rooms</th>
                                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 8px;">BSIT 4D</td>
                                <td>
                                        <button id="editRoom">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button id="deleteRoom">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal for Adding Class -->
            <div class="addClassModal" id="addClassModal">
                <div class="addClassModalContent">
                    <h2>Enter Class</h2>
                    <form id="addClassForm">
                        <div class="floating-label">
                            <input type="text" id="course" placeholder=" " required autocomplete="off" />
                            <label for="course">Course</label>
                        </div>
                        <div class="floating-label">
                            <input type="text" id="year" placeholder=" " required autocomplete="off" />
                            <label for="year">Enter Year</label>
                        </div>
                        <div class="floating-label">
                            <input type="text" id="section" placeholder=" " required autocomplete="off" />
                            <label for="section">Enter Section</label>
                        </div>
                        <div class="deleteButtons">
                            <button type="submit" id="confirmSave">Save</button>
                            <button id="cancelDelete" type="button">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

             <!-- Modal for Adding Room -->
             <div class="addRoomModal" id="addRoomModal">
                <div class="addRoomModalContent">
                    <h2>Enter Room</h2>
                    <form action="">
                        <div class="floating-label">
                            <input type="text" id="room" placeholder=" " required autocomplete="off"/>
                            <label for="room">Room Name</label>
                        </div>
                        <div class="deleteButtons">
                            <button id="saveRoom">Save</button>
                            <button id="cancelAddRoom">Cancel</button>
                        </div>
                    </form>
                </div>
             </div>

             <!-- Modal for Editing Class -->
             <div class="editClassModal" id="editClassModal">
                <div class="editClassModalContent">
                    <h2>Edit Class</h2>
                    <form action="">
                        <div class="floating-label">
                            <input type="text" id="course" value="Your Course Name" placeholder=" " autocomplete="off" readonly />
                            <label for="course">Course</label>
                        </div>
                        <div class="floating-label">
                            <input type="text" id="year" placeholder=" " autocomplete="off"/>
                            <label for="year">Enter Year</label>
                        </div>
                        <div class="floating-label">
                            <input type="text" id="section" placeholder=" " autocomplete="off"/>
                            <label for="section">Enter Section</label>
                        </div>
                        <div class="deleteButtons">
                            <button id="confirmEditClass">Save</button>
                            <button id="cancelEditClass">Cancel</button>
                        </div>
                    </form>
                </div>
             </div>

            <!-- Modal for Editing Room -->
            <div class="editRoomModal" id="editRoomModal">
                <div class="editRoomModalContent">
                    <h2>Edit Room</h2>
                    <form action="">
                        <div class="floating-label">
                            <input type="text" id="room" placeholder=" " autocomplete="off"/>
                            <label for="room">Room</label>
                        </div>
                        <div class="deleteButtons">
                            <button id="confirmEditRoom">Save</button>
                            <button id="cancelEditRoom">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Claas Modal -->
            <div class="deleteClassModal" id="deleteClassModal">
                <div class="deleteClassModalContent">
                    <div class="icon">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <h2>Are You Sure?</h2>
                    <p>Are you sure you want to delete this class?</p>
                    <p>This action cannot be undone</p>
                    <div class="deleteClassButtons">
                        <button id="confirmDeleteClass">Delete Class</button>
                        <button id="cancelDeleteClass">Cancel</button>
                    </div>
                </div>
            </div>

            <!-- Delete Room Modal -->
            <div class="deleteRoomModal" id="deleteRoomModal">
                <div class="deleteRoomModalContent">
                    <div class="icon">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <h2>Are You Sure?</h2>
                    <p>Are you sure you want to delete this room?</p>
                    <p>This action cannot be undone</p>
                    <div class="deleteRoomButtons">
                        <button id="confirmDeleteRoom">Delete Room</button>
                        <button id="cancelDeleteRoom">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Main Content -->

        <!-- Right Section -->
            <?php include '../components/rightbar.php'; ?>
        <!-- End of Right Section -->
    </div>

    <script src="../scripts/manage.js"></script>
    <script src="../index.js"></script>
</body>

</html>