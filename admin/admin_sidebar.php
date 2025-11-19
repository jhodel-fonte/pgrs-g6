
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>

<div class="main-content"></div>
<!-- Sidebar -->
<div class="sidebar">
    <h3 class="text-neon text-center mb-4">Admin Panel</h3>

    <a href="dashboard.php" class="active">Dashboard</a>
    <a href="manage_users.php" class="#">Manage Users</a>
    <a href="manage_reports.php" class="#">Manage Reports</a>
    <a href="../request/logout.php?logout=1">Logout</a>
</div>

<!-- Main content -->
<div class="main-content">
</div>

<script src="admin.js"></script>
</body>
</html>
