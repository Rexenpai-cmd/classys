<?php
$current_page = basename($_SERVER['PHP_SELF']); // Get current file name, e.g. "my-schedules.php"
?>

<head>
    <link rel="stylesheet" href="../../styles/sidebar.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=view_agenda" />
</head>

<aside>
    <div class="sidebar">
        <a href="index.php" data-url="index.php"
           class="<?php echo ($current_page === 'index.php' || $current_page === '') ? 'active' : ''; ?>">
            <span class="material-icons-sharp">schedule</span>
            <h3>My Schedules</h3>
        </a>
        <a href="today-schedules.php" data-url="today-schedules.php" class="<?php echo ($current_page == 'today-schedules.php') ? 'active' : ''; ?>">
            <span class="material-icons-sharp">calendar_month</span>
            <h3>Today's Schedules</h3>
        </a>
        <a href="../../db/logout.php" data-url="../db/logout.php"
           class="<?php echo ($current_page === 'logout.php') ? 'active' : ''; ?>">
            <span class="material-icons-sharp">logout</span>
            <h3>Logout</h3>
        </a>
    </div>
</aside>

<script>
    const sidebarLinks = document.querySelectorAll('.sidebar a');

    sidebarLinks.forEach(link => {
        link.addEventListener("click", function () {
            sidebarLinks.forEach(item => item.classList.remove("active"));
            this.classList.add("active");
        });
    });
</script>
