<?php
session_start();
require '../config/db.php';

// Only allow response team
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'response_team') {
    header("Location: ../login.php");
    exit;
}

$team = $_SESSION['role'];

$stmt = $pdo->prepare("
    SELECT * FROM reports 
    WHERE status = 'Approved'
    AND assigned_team = ?
    ORDER BY created_at DESC
");
$stmt->execute([$team]);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Response Team Dashboard</title>
    <link rel="stylesheet" href="../assets/css/rteam.css">
</head>
<body>

<?php include 'rt_sidebar.php'; ?>

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
</body>
</html>
