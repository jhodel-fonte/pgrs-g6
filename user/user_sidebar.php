<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User</title>
    <!-- Link your CSS -->
    <link rel="stylesheet" href="../assets/css/user.css">
</head>
<body>
<!-- Sidebar -->
<div class="sidebar user-sidebar">
    <h3 class="text text-center mb-4">User Dashboard</h3>
    <a href="dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a>
    <a href="reports.php" class="<?= $current_page == 'reports.php' ? 'active' : ''; ?>">Submit Report</a>
    <a href="view_reports.php" class="<?= $current_page == 'view_reports.php' ? 'active' : ''; ?>">View My Reports</a>
    <a href="../logout.php">Logout</a>
</div>

<!-- Link your JS -->
<script src="../assets/js/user.js"></script>
</body>
</html>