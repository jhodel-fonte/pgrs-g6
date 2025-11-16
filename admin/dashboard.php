<?php
session_start();
require '../config/db.php';


// 1. ADMIN ACCESS CHECK
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: ../login.php");
    exit;
}
// 2. FETCH DASHBOARD COUNTS
$userCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user' AND status = 'Approved'")->fetchColumn();
$teamCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'response'")->fetchColumn();
$finishedCount = $pdo->query("SELECT COUNT(*) FROM reports WHERE status = 'finished'")->fetchColumn();


// 3. MONTHLY REPORT STATISTICS
$stmt = $pdo->query("
    SELECT MONTH(date_submitted) AS month, COUNT(*) AS total
    FROM reports
    GROUP BY MONTH(date_submitted)
    ORDER BY month
");

$monthlyData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$months = array_map(fn($r) => date("F", mktime(0, 0, 0, $r['month'], 1)), $monthlyData);
$totals = array_column($monthlyData, 'total');

// Required for active sidebar
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | Padre Garcia Reporting</title>

<!-- External Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

<?php include 'admin_sidebar.php'; ?>

<div class="main-content">

    <h2 class="text-center dashboard-title">Dashboard Overview</h2>

    <div class="row g-4 mb-4 mt-2 justify-content-center">

        <div class="col-md-3">
            <div class="admin-card">
                <h1 class="count" data-value="<?= $userCount ?>">0</h1>
                <p>Total Users</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="admin-card">
                <h1 class="count" data-value="<?= $teamCount ?>">0</h1>
                <p>Response Team Members</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="admin-card">
                <h1 class="count" data-value="<?= $finishedCount ?>">0</h1>
                <p>Finished Works</p>
            </div>
        </div>

    </div>

    <h3 class="chart-title text-center">Monthly Reports</h3>
    <div class="chart-box">
        <canvas id="monthlyChart"></canvas>
    </div>

</div>

<script>
let chartMonths = <?= json_encode($months) ?>;
let chartTotals = <?= json_encode($totals) ?>;
</script>

<script src="../assets/js/admin.js"></script>

</body>
</html>
