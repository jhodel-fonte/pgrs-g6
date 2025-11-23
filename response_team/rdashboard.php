
<!DOCTYPE html>
<html>
<head>
    <title>Response Team Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/rdashboard.css?v=1">
</head>
<body>

    <!-- Hamburger toggle button for mobile -->
    <button class="sidebar-toggle">&#9776;</button>

    <?php include 'rrt_sidebar.php'; ?>

    <div class="container">
        <h2>Assigned Reports</h2>
        <div class="report-grid">
            <?php
            // Load reports for dashboard. Use centralized PDO config.
            if (!isset($reports)) {
                $reports = [];
                try {
                    require_once __DIR__ . '/../src/data/config.php';
                    $user_id = $_SESSION['user_id'] ?? null;

                    if ($user_id) {
                        // Try assigned_to if available
                        try {
                            $stmt = $pdo->prepare("SELECT * FROM reports WHERE assigned_to = ? ORDER BY created_at DESC");
                            $stmt->execute([$user_id]);
                            $reports = $stmt->fetchAll();
                        } catch (Exception $e) {
                            // assigned_to may not exist — ignore and fallback
                            $reports = [];
                        }
                    }

                    if (empty($reports)) {
                        // Fallback: show recent pending reports
                        $stmt = $pdo->prepare("SELECT * FROM reports WHERE status = 'Pending' ORDER BY created_at DESC LIMIT 20");
                        $stmt->execute();
                        $reports = $stmt->fetchAll();
                    }
                } catch (Exception $e) {
                    error_log('Failed to load reports for dashboard: ' . $e->getMessage());
                    $reports = [];
                }
            }

            if (empty($reports)): ?>
                <p>No assigned reports.</p>
            <?php else: ?>
                <?php foreach ($reports as $r): ?>
                    <div class="report-card">
                        <h3><?= htmlspecialchars($r['name'] ?? $r['title'] ?? ($r['report_type'] ?? 'Report')) ?></h3>
                        <p><?= htmlspecialchars($r['description'] ?? '') ?></p>
                        <p><strong>Location:</strong> <?= htmlspecialchars($r['location'] ?? '') ?></p>
                        <p><strong>Date:</strong> <?= htmlspecialchars($r['created_at'] ?? $r['date_submitted'] ?? '') ?></p>
                        <?php $status = $r['status'] ?? ($r['legit_status'] ?? 'Pending'); ?>
                        <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $status)) ?>"><?= htmlspecialchars($status) ?></span>
                        <br>
                        <a href="rupdate_status.php?id=<?= htmlspecialchars($r['id']) ?>" class="btn-update">Update Status</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- JS Script -->
    <script src="assets/js/rdashboard.js?v=1"></script>
    
</body>
</html>

