<?php

session_start();

// Load centralized DB config
// config.php defines $host, $port, $user, $pass, $db
$configPath = __DIR__ . '/../config.php';
if (file_exists($configPath)) {
    include_once $configPath;
} else {
    error_log('Missing config.php at ' . $configPath);
    // Fail gracefully - keep $reports empty so page still renders
}

$reports = []; // Initialize as array to prevent errors

$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    $conn = new mysqli($host, $user, $pass, $db, $port);
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

    $stmt = $conn->prepare("
        SELECT id, user_id, user_name, category, description, status, created_at, date_submitted
        FROM reports
        WHERE assigned_to = ?
        ORDER BY created_at DESC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $reports = $result->fetch_all(MYSQLI_ASSOC);
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Reports</title>
    <link rel="stylesheet" href="assets/css/rmy_reports.css?v=1">
    <link rel="stylesheet" href="assets/css/rstyle.css?v=1">
</head>
<body>

<!-- Sidebar toggle -->
<button class="sidebar-toggle">&#9776;</button>

<!-- Sidebar -->
<?php include 'rrt_sidebar.php'; ?>

<!-- Main container -->
<div class="container">
    <h2>📁 My Assigned Reports</h2>
    <p class="text-muted">These are the reports currently assigned to you.</p>

    <div class="report-grid">
        <?php if (!empty($reports)): ?>
            <?php foreach ($reports as $r): ?>
                <div class="report-card">
                    <h3><?= htmlspecialchars($r['category'] ?? 'Report') ?></h3>
                    <p><?= htmlspecialchars($r['description']) ?></p>
                    <p><strong>Reporter:</strong> <?= htmlspecialchars($r['user_name'] ?? $r['user_id']) ?></p>
                    <p><strong>Status:</strong> 
                        <?php
                        $status = $r['status'] ?? 'Pending';
                        if ($status == 'Pending') echo '<span class="badge bg-warning text-dark">Pending</span>';
                        elseif ($status == 'In Progress') echo '<span class="badge bg-primary">In Progress</span>';
                        elseif ($status == 'Resolved') echo '<span class="badge bg-success">Resolved</span>';
                        ?>
                    </p>
                    <p><strong>Date:</strong> <?= $r['created_at'] ?? $r['date_submitted'] ?></p>
                    <a href="view_report.php?id=<?= $r['id'] ?>" class="btn-update">View Report</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="report-card text-center">
                <h3>No Reports Yet 📁</h3>
                <p>There are currently no reports assigned to you.</p>
                <p>Check back later or contact your administrator.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="assets/js/rmy_reports.js?v=1"></script>
</body>
</html>
