
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
            <?php if (empty($reports)): ?>
                <p>No assigned reports.</p>
            <?php else: ?>
                <?php foreach ($reports as $r): ?>
                    <div class="report-card">
                        <h3><?= htmlspecialchars($r['title']) ?></h3>
                        <p><?= htmlspecialchars($r['description']) ?></p>
                        <p><strong>Location:</strong> <?= htmlspecialchars($r['location']) ?></p>
                        <p><strong>Date:</strong> <?= $r['date_submitted'] ?></p>
                        <a href="update_status.php?id=<?= $r['id'] ?>" class="btn-update">Update Status</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- JS Script -->
    <script src="assets/js/rdashboard.js?v=1"></script>
</body>
</html>

