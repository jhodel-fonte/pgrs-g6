<?php
session_start();
require_once __DIR__ . '/../src/data/config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Status - Response Team</title>
    <link rel="stylesheet" href="assets/css/rdashboard.css?v=1">
</head>
<body>
<button class="sidebar-toggle">&#9776;</button>
<?php include 'rrt_sidebar.php'; ?>

<div class="container">
    <h2>Update Status</h2>

    <?php
    try {
        $user_id = $_SESSION['user_id'] ?? null;
        // Fetch full report fields so we can render cards
        if ($user_id) {
            $stmt = $pdo->prepare("SELECT * FROM reports WHERE assigned_to = ? ORDER BY created_at DESC");
            $stmt->execute([$user_id]);
            $updates = $stmt->fetchAll();
            if (empty($updates)) {
                $stmt = $pdo->prepare("SELECT * FROM reports ORDER BY created_at DESC LIMIT 50");
                $stmt->execute();
                $updates = $stmt->fetchAll();
            }
        } else {
            $stmt = $pdo->prepare("SELECT * FROM reports ORDER BY created_at DESC LIMIT 50");
            $stmt->execute();
            $updates = $stmt->fetchAll();
        }
    } catch (Exception $e) {
        error_log('Failed loading updates: ' . $e->getMessage());
        $updates = [];
    }
    ?>

    <?php if (empty($updates)): ?>
        <p class="text-muted">No recent updates.</p>
    <?php else: ?>
        <div class="report-grid">
            <?php foreach ($updates as $u): ?>
                <div class="report-card">
                    <h3><?= htmlspecialchars($u['name'] ?? $u['report_type'] ?? 'Report') ?></h3>
                    <p><?= htmlspecialchars($u['description'] ?? '') ?></p>
                    <p><strong>Location:</strong> <?= htmlspecialchars($u['location'] ?? '') ?></p>
                    <p><strong>Date:</strong> <?= htmlspecialchars($u['created_at'] ?? '') ?></p>
                    <?php $status = $u['status'] ?? ($u['legit_status'] ?? 'Pending'); ?>
                    <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $status)) ?>"><?= htmlspecialchars($status) ?></span>
                    <br>
                    <a href="rupdate_status.php?id=<?= htmlspecialchars($u['id']) ?>" class="btn-update">Update Status</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

</body>
</html>
