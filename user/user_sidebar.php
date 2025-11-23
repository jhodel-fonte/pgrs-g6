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
            <img src="../assets/uploads/profile/profile_6909db8442615.jpg" class="profile-img" onclick="toggleProfileMenu()">
            <div class="profile-dropdown" id="profileDropdown">
                <a href="my_profile.php"><i class="fa-solid fa-user me-2"></i> My Profile</a>
                <a href="../logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="../assets/js/user.js"></script>

</body>
</html>
