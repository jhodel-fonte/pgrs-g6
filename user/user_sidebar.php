<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/user.css">
    
    <!-- Font Awesome (you need this for the bell icon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<!-- Hamburger toggle button for mobile -->
<button class="sidebar-toggle" aria-expanded="false">☰</button>

<!-- Sidebar -->
<div class="sidebar user-sidebar">
    <h3 class="text text-center mb-4">User Dashboard</h3>

    <a href="dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a>
    <a href="reports.php" class="<?= $current_page == 'reports.php' ? 'active' : ''; ?>">Submit Report</a>
    <a href="view_reports.php" class="<?= $current_page == 'view_reports.php' ? 'active' : ''; ?>">View My Reports</a>
    <a href="../logout.php">Logout</a>
</div>

<!-- Top Bar -->
<div class="topbar">
    <div class="topbar-right">
        <span class="date-display" id="dateDisplay"></span>

        <div class="notification-bell" onclick="toggleNotifications()">
            <i class="fa-solid fa-bell notification-bell" id="notificationBell"></i>
            <span class="notification-badge" id="notificationCount">3</span>

            <div class="notification-dropdown" id="notificationDropdown">
                <!-- Notifications will be populated here -->
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="../assets/js/user.js"></script>

</body>
</html>
