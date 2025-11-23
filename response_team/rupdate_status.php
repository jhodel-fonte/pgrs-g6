<?php
session_start();

// Load centralized DB config
// config.php defines $host, $port, $user, $pass, $db
$configPath = __DIR__ . '/../config.php';
if (file_exists($configPath)) {
    include_once $configPath;
} else {
    error_log('Missing config.php at ' . $configPath);
    header("HTTP/1.1 500 Internal Server Error");
    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Configuration Error</title>';
    echo '<style>body{font-family:Segoe UI, Tahoma, Geneva, Verdana, sans-serif;background:#111827;color:#fff;padding:24px} .card{background:#1f2937;padding:20px;border-radius:8px;}</style>';
    echo '</head><body><div class="card"><h2>Configuration missing</h2><p>Database configuration file not found. Please create <code>config.php</code> in the project root.</p></div></body></html>';
    exit;
}

// Try to connect, but handle errors gracefully so we don't expose stack traces
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli($host, $user, $pass, $db, $port);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $ex) {
    // Log the real error for the developer, but show a friendly message to the user
    error_log('DB connection failed: ' . $ex->getMessage());
    header("HTTP/1.1 500 Internal Server Error");
    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Database Error</title>';
    echo '<style>body{font-family:Segoe UI, Tahoma, Geneva, Verdana, sans-serif;background:#111827;color:#fff;padding:24px} .card{background:#1f2937;padding:20px;border-radius:8px;}</style>';
    echo '</head><body><div class="card"><h2>Database connection failed</h2><p>Please check your database credentials in <code>config.php</code> and ensure MySQL is running.</p></div></body></html>';
    exit;
}

// Get report ID from URL
$report_id = $_GET['id'] ?? null;

// Initialize $report
$report = null;

if ($report_id) {
    $stmt = $conn->prepare("SELECT * FROM reports WHERE id = ?");
    $stmt->bind_param("i", $report_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $report = $result->fetch_assoc();
    }
    $stmt->close();
}

$conn->close();
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
