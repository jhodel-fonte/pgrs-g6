<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only allow if admin is logged in
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <!-- Link your CSS -->
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<!-- Hamburger toggle button for mobile -->
<button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>

<!-- Sidebar -->
<div class="sidebar">
    <h3 class="text-neon text-center mb-4">Admin Panel</h3>

    <a href="dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">Dashboard</a>
    <a href="manage_users.php" class="<?php echo ($current_page == 'manage_users.php') ? 'active' : ''; ?>">Manage Users</a>
    <a href="manage_reports.php" class="<?php echo ($current_page == 'manage_reports.php') ? 'active' : ''; ?>">Manage Reports</a>
    <a href="../logout.php">Logout</a>
</div>

<!-- Main content wrapper -->
<div class="main-content">
    <!-- Your page content goes here -->
</div>

<!-- Link your JS -->
<script src="admin.js"></script>
</body>
</html>
