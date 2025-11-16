<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'response_team') {
    header("Location: ../login.php");
    exit;
}
?>
<div class="sidebar">
    <h2 class="sidebar-title">Response Team</h2>

    <ul class="menu-list">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="my_reports.php">My Assigned Reports</a></li>
        <li><a href="../logout.php" class="logout">Logout</a></li>
    </ul>
</div>
