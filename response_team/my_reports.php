<?php
session_start();
require '../config/db.php';

// 🔒 Only allow response team members
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'response_team') {
    header("Location: ../login.php");
    exit;
}

$username = $_SESSION['username']; // The team member’s username

// Fetch reports assigned to this team member
$stmt = $pdo->prepare("SELECT * FROM reports WHERE assigned_team = ? ORDER BY id DESC");
$stmt->execute([$username]);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include 'rt_sidebar.php'; ?>

<div class="container mt-4" style="margin-left:260px;">
    <h2 class="fw-bold mb-3">📁 My Assigned Reports</h2>
    <p class="text-muted">These are the reports currently assigned to you.</p>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Reporter</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Date Reported</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($reports) > 0): ?>
                            <?php foreach ($reports as $r): ?>
                                <tr>
                                    <td><?= $r['id'] ?></td>
                                    <td><?= htmlspecialchars($r['user_name'] ?? $r['user_id']) ?></td>
                                    <td><?= htmlspecialchars($r['category'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($r['description']) ?></td>
                                    <td>
                                        <?php
                                        $status = $r['status'] ?? 'Pending';
                                        if ($status == 'Pending') echo '<span class="badge bg-warning text-dark">Pending</span>';
                                        elseif ($status == 'In Progress') echo '<span class="badge bg-primary">In Progress</span>';
                                        elseif ($status == 'Resolved') echo '<span class="badge bg-success">Resolved</span>';
                                        ?>
                                    </td>
                                    <td><?= $r['created_at'] ?? $r['date_submitted'] ?></td>
                                    <td>
                                        <a href="view_report.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-3 text-muted">
                                    No assigned reports yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
