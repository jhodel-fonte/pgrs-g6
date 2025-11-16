<?php
session_start();
require '../config/db.php';

// Redirect if not logged in or not a user
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'user') {
    header("Location: ../login.php");
    exit;
}

// Get user data
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch report counts
$total_reports = $pending_reports = $resolved_reports = 0;

$report_stmt = $pdo->prepare("SELECT status, COUNT(*) as count FROM reports WHERE user_id = ? GROUP BY status");
$report_stmt->execute([$user_id]);
while ($row = $report_stmt->fetch(PDO::FETCH_ASSOC)) {
    $total_reports += $row['count'];
    if (strtolower($row['status']) === 'pending') $pending_reports = $row['count'];
    if (strtolower($row['status']) === 'resolved' || strtolower($row['status']) === 'approved') $resolved_reports = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Dashboard | Padre Garcia Reporting</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #050505, #0b0b0b);
    color: #fff;
    min-height: 100vh;
}
.text-neon {
    color: #00ffff;
}
.border-neon {
    border: 1px solid rgba(0, 255, 255, 0.4) !important;
    box-shadow: 0 0 15px rgba(0, 255, 255, 0.3);
}
.card {
    border-radius: 15px;
    transition: 0.3s ease;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0 20px rgba(0, 255, 255, 0.4);
}
.card.bg-secondary {
    background: rgba(60, 60, 60, 0.8);
}
.card.bg-dark {
    background: rgba(20, 20, 20, 0.85);
}
.btn-primary {
    background-color: #f0f2f2ff;
    color: #000;
    border: none;
    font-weight: bold;
    transition: 0.3s;
}
.btn-primary:hover {
    background-color: #00cccc;
    color: #000;
    transform: scale(1.05);
}
.btn-outline-info {
    background-color: #f0f2f2ff;
    border: none;
    color: #000;
    font-weight: bold;
    transition: 0.3s;
}
.btn-outline-info:hover {
    background-color: #00ffff;
    color: #000;
    transform: scale(1.05);
}
.btn-danger.btn-sm {
    background-color: #ff3b3b;
    border: none;
}
.btn-danger.btn-sm:hover {
    background-color: #ff6666;
    transform: scale(1.05);
}
.rounded-circle {
    border: 3px solid #00ffff;
    box-shadow: 0 0 10px rgba(0, 255, 255, 0.4);
}
.report-stat {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    padding: 15px;
    text-align: center;
    color: #fff;
    transition: 0.3s;
}
.report-stat:hover {
    transform: scale(1.05);
    background: rgba(0, 255, 255, 0.1);
}
.report-stat h3 {
    color: #00ffff;
    text-shadow: 0 0 10px #00ffff;
}
</style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-neon">Welcome, <?= htmlspecialchars($user['firstname']); ?> 👋</h2>
        <a href="../logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>

    <div class="row g-4">
        <!-- Profile Card -->
        <div class="col-md-4">
            <div class="card bg-secondary text-center shadow border-neon">
                <div class="card-body">
                    <img src="<?= !empty($user['profile_pic']) ? '../uploads/profile/' . htmlspecialchars($user['profile_pic']) : '../assets/default_user.png'; ?>" 
                         alt="Profile" class="rounded-circle mb-3" width="100" height="100">
                    <h5><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></h5>
                    <p class="mb-1 text-light"><?= htmlspecialchars($user['email']); ?></p>
                    <p class="text-light"><?= htmlspecialchars($user['mobile_number']); ?></p>
                </div>
            </div>
        </div>

        <!-- Reports Overview -->
        <div class="col-md-8">
            <div class="card bg-dark border-neon p-4 shadow">
                <h4 class="text-neon mb-3"><i class="fa-solid fa-clipboard-list"></i> Your Reports</h4>
                <p class="text-light">View, submit, and track your submitted reports here.</p>

                <!-- Reports Summary -->
                <div class="row text-center mb-4">
                    <div class="col-md-4">
                        <div class="report-stat">
                            <h3><?= $total_reports; ?></h3>
                            <p>Total Reports</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="report-stat">
                            <h3><?= $pending_reports; ?></h3>
                            <p>Pending</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="report-stat">
                            <h3><?= $resolved_reports; ?></h3>
                            <p>Approved</p>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-sm-flex">
                    <a href="reports.php" class="btn btn-primary"> Submit New Report</a>
                    <a href="view_reports.php" class="btn btn-outline-info"> View My Reports </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FontAwesome for icons -->
<script src="https://kit.fontawesome.com/a2e0e6ad15.js" crossorigin="anonymous"></script>

</body>
</html>
