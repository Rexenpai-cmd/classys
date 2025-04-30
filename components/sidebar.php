<?php
$current_page = basename($_SERVER['PHP_SELF']); // Get the current page name
?>

<head>
    <link rel="stylesheet" href="../styles/sidebar.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=view_agenda" />
</head>
<aside>
    <div class="sidebar">
        <a href="index.php" data-url="index.php" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
            <span class="material-icons-sharp">dashboard</span>
            <h3>Dashboard</h3>
        </a>
        <a href="schedules.php" data-url="schedules.php" class="<?php echo ($current_page == 'schedules.php') ? 'active' : ''; ?>">
            <span class="material-icons-sharp">calendar_month</span>
            <h3>Schedule</h3>
        </a>
        <a href="my-schedules.php" data-url="my-schedules.php" class="<?php echo ($current_page == 'my-schedules.php') ? 'active' : ''; ?>">
            <span class="material-icons-sharp">schedule</span>
            <h3>My Schedules</h3>
        </a>
        <a href="subjects.php" data-url="subjects.php" class="<?php echo ($current_page == 'subjects.php') ? 'active' : ''; ?>">
            <span class="material-symbols-outlined">view_agenda</span>
            <h3>Subjects</h3>
        </a>
        <a href="instructors.php" data-url="instructors.php" class="<?php echo ($current_page == 'instructors.php') ? 'active' : ''; ?>">
            <span class="material-icons-sharp">person</span>
            <h3>Instructors</h3>
        </a>
        <a href="manage.php" data-url="manage.php" class="<?php echo ($current_page == 'manage.php') ? 'active' : ''; ?>">
            <span class="material-icons-sharp">settings</span>
            <h3>Manage</h3>
        </a>
        <a href="../db/logout.php" data-url="../db/logout.php" class="<?php echo ($current_page == '../db/logout.php') ? 'active' : ''; ?>">
            <span class="material-icons-sharp">logout</span>
            <h3>Logout</h3>
        </a>
    </div>
</aside>

<script>
    const sidebarLinks = document.querySelectorAll('.sidebar a');

    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            sidebarLinks.forEach(item => item.classList.remove('active'));
            this.classList.add('active');
        });
    });
</script>