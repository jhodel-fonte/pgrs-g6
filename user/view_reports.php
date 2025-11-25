<?php
session_start();
require '../config/db.php';

// Redirect if not logged in or not a user
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'user') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// -------------------------------
// FILTER HANDLING
// -------------------------------
$filter = isset($_GET['filter']) ? strtolower($_GET['filter']) : '';

$query = "SELECT * FROM reports WHERE user_id = ?";
$params = [$user_id];

// Apply filters from dashboard cards
if ($filter === 'pending') {
    $query .= " AND status = 'pending'";
}
elseif ($filter === 'approved') {
    $query .= " AND (status = 'approved' OR status = 'resolved' OR status = 'finished')";
}

$query .= " ORDER BY id DESC";

// Fetch reports
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Reports | Padre Garcia Reporting</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { 
    background-color: #fff; 
    color: #333; 
}

/* TEXT COLOR */
.text-color { 
    color: #4e89ff; 
}

/* TABLE ALIGNMENT */
.table-dark th, 
.table-dark td { 
    vertical-align: middle; 
}

/* STATUS COLORS */
.status-pending { 
    color: #ffc107; 
    font-weight: bold; 
}
.status-ongoing { 
    color: #17a2b8; 
    font-weight: bold; 
}
.status-finished { 
    color: #28a745; 
    font-weight: bold; 
}

/* CARD */
.card-custom { 
    background-color: #606268ff; 
    border: 1px; 
    border-radius: 10px; 
    padding: 20px; 
}

/* BUTTONS (Back to Dashboard & others) */
.btn {
    background-color: #0d6efd; /* new button color */
    color: #fff; 
    font-weight: bold;
    border: none;
    transition: 0.3s;
}

.btn:hover {
    opacity: 0.8;
}

</style>
</head>
<body>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-neon">📋 My Reports</h2>
        <a href="dashboard.php" class="btn btn-primary btn-sm">⬅ Back to Dashboard</a>
    </div>

    <div class="card-custom">

        <?php if (count($reports) > 0): ?>
        <table class="table table-dark table-bordered table-hover text-center align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reports as $index => $report): ?>
                <tr>
                    <td><?= $index + 1; ?></td>
                    <td><?= htmlspecialchars($report['title']); ?></td>
                    <td><?= htmlspecialchars($report['category']); ?></td>

                    <td>
                        <?php
                        $status = strtolower($report['status']);
                        if ($status === 'pending') {
                            echo '<span class="status-pending">Pending</span>';
                        } elseif ($status === 'ongoing') {
                            echo '<span class="status-ongoing">Ongoing</span>';
                        } elseif ($status === 'finished' || $status === 'approved' || $status === 'resolved') {
                            echo '<span class="status-finished">Finished</span>';
                        } else {
                            echo '<span>Unknown</span>';
                        }
                        ?>
                    </td>

                    <td><?= $report['created_at'] ?? 'N/A'; ?></td>

                    <td>
                        <a href="report_details.php?id=<?= $report['id']; ?>" class="btn btn-sm btn-info">View</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php else: ?>
            <p class="text-center text-muted">You haven't submitted any reports yet.</p>
        <?php endif; ?>

    </div>
</div>

</body>
</html>
