<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "newclass"); // Replace with your DB details

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination variables
$itemsPerPage = 10; // Number of items per page
$totalItemsQuery = "SELECT COUNT(*) as count FROM subjects";
$totalItemsResult = $conn->query($totalItemsQuery);
$totalItems = $totalItemsResult->fetch_assoc()['count'];
$totalPages = ceil($totalItems / $itemsPerPage);

// Get current page from URL, default to 1
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, min($currentPage, $totalPages)); // Ensure current page is within bounds

// Calculate the offset for the SQL query
$offset = ($currentPage - 1) * $itemsPerPage;

// Query to fetch subjects
$sql = "SELECT id, section_code, subject_code, subject_title, year, unit FROM subjects LIMIT $offset, $itemsPerPage";
$result = $conn->query($sql);

$subjects = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
}

$conn->close();

// Check if it's an AJAX request
if (isset($_GET['ajax'])) {
    echo json_encode($subjects);
    exit;
}
?>

<!-- HTML & JS code follows -->

<head>
    <link rel="stylesheet" href="../styles/subjects.css">
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

        <!-- Main Content -->
        <div class="subjects-container">
            <div class="header">
                <h2>All Subjects</h2>
                <div class="actions-container">
                    <button id="addSubjectBtn">
                        <i class="fa-solid fa-plus"></i>
                        Add Subject
                    </button>
                </div>
            </div>
            <div class="scrollable-table">
                <table class="subjects-table">
                    <thead>
                        <tr> 
                            <th>Section Code</th>
                            <th>Subject Code</th>
                            <th>Subject Title</th>
                            <th>Year</th>
                            <th>Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="subjectsTableBody">
                        <!-- Subjects will be loaded here via AJAX -->
                    </tbody>
                </table>
            </div>
            <div class="paginator">
                <div class="pages">
                    <h3>
                        Results: 
                        <?php 
                            $start = $offset + 1;
                            $end = min($offset + $itemsPerPage, $totalItems);
                            echo "$start - $end of $totalItems";
                        ?>
                    </h3>
                </div>
                <?php if ($totalPages > 1): ?>
                    <nav>
                        <ul class="pagination">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item<?php echo $i === $currentPage ? ' active' : ''; ?>">
                                    <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
        <!-- Add Modal -->
        <div id="subjectModal" class="modal">
            <div class="modal-content">
                <p>Add Subject</p>
                <form id="subjectForm">
                    <div class="floating-label">
                        <input type="text" id="class" name="section_code" placeholder=" " required autocomplete="off"/>
                        <label for="class">Section Code</label>
                    </div>

                    <div class="floating-label">
                        <input type="text" id="instructor" name="subject_code" placeholder=" " required autocomplete="off"/>
                        <label for="instructor">Subject Code</label>
                    </div>

                    <div class="floating-label">
                        <input type="text" id="subject_title" name="subject_title" placeholder=" " required autocomplete="off"/>
                        <label for="subject_title">Subject Title</label>
                    </div>

                    <div class="floating-label">
                        <input type="text" id="year" name="year" placeholder=" " required autocomplete="off"/>
                        <label for="year">Year</label>
                    </div>

                    <div class="floating-label">
                        <input type="text" id="unit" name="unit" placeholder=" " required autocomplete="off"/>
                        <label for="unit">Unit</label>
                    </div>

                    <div class="modal-buttons">
                        <button type="button" id="closeModal">Cancel</button>
                        <button type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div id="editSubjectModal" class="modal">
            <div class="modal-content">
                <p>Edit Subject</p>
                <form id="editSubjectForm">
                    <input type="hidden" id="editid" name="editid" />
                    <div class="floating-label">
                        <input type="text" id="editsection" name="editsection" placeholder=" " required autocomplete="off"/>
                        <label for="class">Section Code</label>
                    </div>

                    <div class="floating-label">
                        <input type="text" id="editsubject" name="editsubject" placeholder=" " required autocomplete="off"/>
                        <label for="instructor">Subject Code</label>
                    </div>

                    <div class="floating-label">
                        <input type="text" id="edittitle" name="edittitle" placeholder=" " required autocomplete="off"/>
                        <label for="subject_title">Subject Title</label>
                    </div>

                    <div class="floating-label">
                        <input type="text" id="edityear" name="edityear" placeholder=" " required autocomplete="off"/>
                        <label for="year">Year</label>
                    </div>

                    <div class="floating-label">
                        <input type="text" id="editunit" name="editunit" placeholder=" " required autocomplete="off"/>
                        <label for="unit">Unit</label>
                    </div>

                    <div class="modal-buttons">
                        <button type="button" id="closeEditModal">Cancel</button>
                        <button type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="deleteModal" id="deleteModal">
            <div class="deleteModalContent">
                <div class="icon">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h2>Are You Sure?</h2>
                <p>Are you sure you want to delete this subject?</p>
                <p>This action cannot be undone</p>
                <div class="deleteButtons">
                    <button id="confirmDelete">Delete Subject</button>
                    <button id="cancelDelete">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../index.js"></script>
    <script>
        const totalItems = <?php echo json_encode($totalItems); ?>; // Output total items
        const itemsPerPage = <?php echo json_encode($itemsPerPage); ?>; // Output items per page
    </script>
    <script src="../scripts/subjects.js"></script>
</body>