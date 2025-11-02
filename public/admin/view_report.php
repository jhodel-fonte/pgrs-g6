<?php
require_once '../config/config.php';
session_start();

// Optional: only allow admin to access
// if (!isset($_SESSION['admin_logged_in'])) {
//     header("Location: ../login/login.php");
//     exit();
// }

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_id'], $_POST['new_status'])) {
    $report_id = intval($_POST['report_id']);
    $new_status = $_POST['new_status'];
    $allowed_status = ['Pending','Approved','Rejected'];

    if (in_array($new_status, $allowed_status)) {
        $stmt = $pdo->prepare("UPDATE reports SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $report_id]);
        header("Location: view_reports.php"); // reload page
        exit;
    }
}

try {
    $stmt = $pdo->query("SELECT * FROM reports ORDER BY created_at DESC");
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Reports - Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body { margin:0; font-family:"Open Sans", sans-serif; display:flex; background:#f5f9ff; }
.container { display:flex; width:100%; height:100vh; }
.sidebar { width:250px; background:#004aad; color:#fff; padding:20px; }
.sidebar h2 { text-align:center; margin-bottom:30px; }
.sidebar ul { list-style:none; padding:0; }
.sidebar ul li { margin:15px 0; }
.sidebar ul li a { color:#fff; text-decoration:none; font-weight:600; display:block; padding:10px; border-radius:8px; transition:0.3s; }
.sidebar ul li a:hover, .sidebar ul li a.active { background:#1e64d8; }
.main-content { flex:1; padding:30px; overflow-y:auto; }
h1 { color:#004aad; margin-bottom:15px; }
.report-card { background:white; padding:20px; border-radius:10px; box-shadow:0 2px 5px rgba(0,0,0,0.1); margin-bottom:20px; display:flex; gap:20px; }
.report-photo { width:150px; height:150px; object-fit:cover; border-radius:8px; }
.report-details { flex:1; }
.report-details h3 { margin:0 0 10px; }
.report-details p { margin:5px 0; }
.status-badge { padding:5px 10px; border-radius:20px; color:white; font-size:13px; display:inline-block; margin-bottom:10px; }
.status-pending { background:#f0ad4e; }
.status-approved { background:#5cb85c; }
.status-rejected { background:#d9534f; }
button { padding:8px 15px; border:none; border-radius:6px; font-weight:600; cursor:pointer; margin-right:5px; }
button.approve { background:#5cb85c; color:white; }
button.reject { background:#d9534f; color:white; }
button.pending { background:#f0ad4e; color:white; }
</style>
</head>
<body>
<div class="container">
    <aside class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="../admin/admin_dashboard.php">Dashboard</a></li>
            <li><a href="../admin/view_reports.php" class="active">View Reports</a></li>
            <li><a href="../login/logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <h1>User Reports</h1>
        <?php if(empty($reports)): ?>
            <p>No reports submitted yet.</p>
        <?php else: ?>
            <?php foreach($reports as $report): ?>
            <div class="report-card">
                <?php if(!empty($report['photo']) && file_exists("../".$report['photo'])): ?>
                    <img src="../<?= htmlspecialchars($report['photo']) ?>" alt="Report Photo" class="report-photo">
                <?php else: ?>
                    <img src="https://via.placeholder.com/150" alt="No Photo" class="report-photo">
                <?php endif; ?>

                <div class="report-details">
                    <h3><?= htmlspecialchars($report['name']) ?> - <?= htmlspecialchars($report['report_type']) ?></h3>
                    <span class="status-badge 
                        <?= $report['status']=='Pending'?'status-pending':($report['status']=='Approved'?'status-approved':'status-rejected') ?>">
                        <?= htmlspecialchars($report['status']) ?>
                    </span>
                    <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($report['description'])) ?></p>
                    <p><strong>ML Category:</strong> <?= htmlspecialchars($report['ml_category'] ?? '-') ?></p>
                    <p><strong>ML Summary:</strong> <?= htmlspecialchars($report['ml_summary'] ?? '-') ?></p>
                    <p><strong>Legit:</strong> <?= isset($report['ml_legit']) ? ($report['ml_legit'] ? 'Yes' : 'No') : '-' ?></p>
                    <p><strong>Delay:</strong> <?= isset($report['ml_delay']) ? ($report['ml_delay'] ? 'Delayed' : 'On-Time') : '-' ?></p>
                    <p><strong>Location:</strong> <?= htmlspecialchars($report['location']) ?> (Lat: <?= htmlspecialchars($report['latitude']) ?>, Lng: <?= htmlspecialchars($report['longitude']) ?>)</p>
                    <p><strong>Reported On:</strong> <?= htmlspecialchars($report['created_at']) ?></p>

                    <form method="POST" style="margin-top:10px;">
                        <input type="hidden" name="report_id" value="<?= $report['id'] ?>">
                        <button type="submit" name="new_status" value="Approved" class="approve">Approve</button>
                        <button type="submit" name="new_status" value="Rejected" class="reject">Reject</button>
                        <button type="submit" name="new_status" value="Pending" class="pending">Pending</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</div>
</body>
</html>
