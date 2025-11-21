
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <!-- Link your CSS -->
    <link rel="stylesheet" href="../admin/assets/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<!-- Hamburger toggle button for mobile -->
<button class="sidebar-toggle" aria-expanded="false">☰</button>

<!-- Sidebar -->
 <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
<div class="sidebar">
    <h3 class="text text-center mb-4">Admin Panel</h3>

    <a href="dashboard.php" class="<?php echo ($currentPage === 'dashboard.php') ? 'active' : ''; ?>"><i class="fa-solid fa-gauge me-2"></i> Dashboard</a>
    <a href="manage_users.php" class="<?php echo ($currentPage === 'manage_users.php') ? 'active' : ''; ?>"><i class="fa-solid fa-users me-2"></i> Manage Users</a>
    <a href="manage_reports.php" class="<?php echo ($currentPage === 'manage_reports.php') ? 'active' : ''; ?>"><i class="fa-solid fa-file-alt me-2"></i> Manage Reports</a>
    <a href="activity_log.php" class="<?php echo ($currentPage === 'activity_log.php') ? 'active' : ''; ?>"><i class="fa-solid fa-list-check me-2"></i> Activity Log</a>
    
</div>

<!-- Top Bar -->
<div class="topbar">
    <div class="topbar-right">
        <span class="date-display" id="dateDisplay"></span>

        <div class="notification-bell" onclick="toggleNotifications()">
            <i class="fa-solid fa-bell notification-bell" id="notificationBell" onclick="toggleNotificationMenu()"></i>
            <span class="notification-badge" id="notificationCount">3</span>

            <div class="notification-dropdown" id="notificationDropdown">
                <!-- Notifications will be populated here -->
            </div>
        </div>

        <div class="profile-menu">
            <img src="../assets/img/logo.png" class="profile-img" onclick="toggleProfileMenu()">
            <div class="profile-dropdown" id="profileDropdown">
                <a href="my_profile.php"><i class="fa-solid fa-user me-2"></i> My Profile</a>
                <a href="../logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a>
            </div>
        </div>
    </div>
</div>


<!-- Link your JS -->
<script src="../admin/assets/admin.js"></script>
</body>
</html>
