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
        // If user-specific assigned reports are desired, filter by assigned_to
        if ($user_id) {
            $stmt = $pdo->prepare("SELECT id, name, report_type, status, created_at FROM reports WHERE assigned_to = ? ORDER BY created_at DESC");
            $stmt->execute([$user_id]);
            $updates = $stmt->fetchAll();
            // If none, fall back to recent
            if (empty($updates)) {
                $stmt = $pdo->prepare("SELECT id, name, report_type, status, created_at FROM reports ORDER BY created_at DESC LIMIT 50");
                $stmt->execute();
                $updates = $stmt->fetchAll();
            }
        } else {
            $stmt = $pdo->prepare("SELECT id, name, report_type, status, created_at FROM reports ORDER BY created_at DESC LIMIT 50");
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
        <ul class="updates-list">
            <?php foreach ($updates as $u): ?>
                <li>
                    <div class="update-item">
                        <div class="update-title"><?= htmlspecialchars($u['name'] ?? $u['report_type'] ?? 'Report') ?></div>
                        <div class="update-meta">
                            <span class="update-date"><?= htmlspecialchars($u['created_at']) ?></span>
                            <span class="update-status status-<?= strtolower(str_replace(' ', '-', $u['status'] ?? 'pending')) ?>"><?= htmlspecialchars($u['status'] ?? 'Pending') ?></span>
                            <a href="rupdate_status.php?id=<?= htmlspecialchars($u['id']) ?>" class="btn-update small">Update</a>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</div>

</body>
</html>
