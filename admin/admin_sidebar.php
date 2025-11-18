
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

<div class="main-content"></div>
<!-- Sidebar -->
<div class="sidebar">
    <h3 class="text-neon text-center mb-4">Admin Panel</h3>

    <a href="dashboard.php" class="active">Dashboard</a>
    <a href="manage_users.php" class="#">Manage Users</a>
    <a href="manage_reports.php" class="#">Manage Reports</a>
    <a href="../logout.php">Logout</a>
</div>

<!-- Main content wrapper -->
<div class="main-content">
</div>

<!-- Link your JS -->
<script src="admin.js"></script>
</body>
</html>
