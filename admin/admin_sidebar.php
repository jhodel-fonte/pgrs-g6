<?php
require_once __DIR__ .'../../src/utillities/sessionRouting.php';
require_once __DIR__ .'../../src/modules/reports.php';

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="./assets/admin.css">
</head>
<body>

<button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>

<div class="main-content"></div>
<!-- Sidebar -->
<div class="sidebar">
    <h3 class="text-neon text-center mb-4">Admin Panel</h3>

    <a href="dashboard.php" class="<?php echo ($currentPage === 'dashboard.php') ? 'active' : ''; ?>">Dashboard</a>
    <a href="manage_users.php" class="<?php echo ($currentPage === 'manage_users.php') ? 'active' : ''; ?>">Manage Users</a>
    <a href="manage_reports.php" class="<?php echo ($currentPage === 'manage_reports.php') ? 'active' : ''; ?>">Manage Reports</a>
    <a href="../handlers/logout.php?logout=1">Logout</a>
</div>

<!-- Main content -->
<div class="main-content">
</div>

<script src="admin.js"></script>
</body>
</html>
