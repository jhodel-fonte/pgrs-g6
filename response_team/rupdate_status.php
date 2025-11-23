<?php
session_start();

// Use centralized PDO config
require_once __DIR__ . '/../src/data/config.php';

// Get report ID from URL
$report_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Initialize $report
$report = null;

if ($report_id) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM reports WHERE id = ?');
        $stmt->execute([$report_id]);
        $report = $stmt->fetch();
    } catch (PDOException $e) {
        error_log('Failed to fetch report ' . $report_id . ': ' . $e->getMessage());
        $report = null;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Report Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Dashboard-style CSS -->
    <link href="assets/css/rmy_reports.css?v=1" rel="stylesheet">
</head>
<body>

<!-- Sidebar toggle -->
<button class="sidebar-toggle">&#9776;</button>

<!-- Sidebar -->
<?php include 'rrt_sidebar.php'; ?>

<!-- Main content -->
<div class="container mt-4">
    <h2 class="mb-3 text-light">Update Report Status</h2>

    <?php if ($report): ?>
        <div class="report-card">
            <h3><?= htmlspecialchars($report['report_type'] ?? $report['category'] ?? 'Report'); ?></h3>
            <p><?= htmlspecialchars($report['description']); ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($report['status'] ?? 'Pending'); ?></p>

            <form method="POST" action="rupdate_status_action.php">
                <input type="hidden" name="id" value="<?= $report['id']; ?>">

                <label class="form-label">Select Status</label>
                <select name="status" class="form-control mb-3" required>
                    <option value="Responded" <?= ($report['status'] == 'Responded') ? 'selected' : ''; ?>>Responded</option>
                    <option value="Completed" <?= ($report['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                </select>

                <button type="submit" class="btn-update w-100">Update Status</button>
            </form>
        </div>
    <?php else: ?>
        <div class="report-card text-center">
            <h3 class="text-danger">Report Not Found ❌</h3>
            <p>The report you are trying to access does not exist.</p>
        </div>
    <?php endif; ?>
</div>

<!-- JS -->
<script src="assets/js/rmy_reports.js?v=1"></script>
</body>
</html>
