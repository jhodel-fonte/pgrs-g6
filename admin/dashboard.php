<?php
session_start();

require_once __DIR__ .'../../src/data/reports.php';
$reports = getAllReports([]); // Get all reports with default options

// alam mo na to
$users = [
    ["id" => 1, "name" => "John Doe"],
    ["id" => 2, "name" => "Jane Smith"],
    ["id" => 3, "name" => "Carlos Reyes"]
];
$userCount = count($users);


$responseTeams = [
    ["id" => 1, "name" => "Firefighter Team"],
    ["id" => 2, "name" => "Police Team"]
];
$teamCount = count($responseTeams);


$finishedReports = [
    ["id" => 1, "status" => "Completed"],
    ["id" => 2, "status" => "Completed"],
    ["id" => 3, "status" => "Completed"],
    ["id" => 4, "status" => "Completed"]
];
$finishedCount = count($finishedReports);

$months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun"];
$totals = [5, 8, 12, 20, 15, 9];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | Padre Garcia Reporting</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="./assets/admin.css">
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


