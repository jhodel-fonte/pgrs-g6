<?php
session_start();
require_once '../config/db.php';

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
    if (in_array(strtolower($row['status']), ['resolved','approved'])) $resolved_reports = $row['count'];
}

// Current page for active sidebar link
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Dashboard | Padre Garcia Reporting</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/user.css">
<script src="https://kit.fontawesome.com/a2e0e6ad15.js" crossorigin="anonymous"></script>
</head>
<body>

<!-- SIDEBAR -->
<button class="sidebar-toggle">☰</button>
<div class="sidebar user-sidebar">
    <h3 class="text-center mb-4">User Panel</h3>
    <a href="dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : ''; ?>"><i class="fa-solid fa-gauge me-2"></i> Dashboard</a>
    <a href="reports.php" class="<?= $current_page == 'reports.php' ? 'active' : ''; ?>"><i class="fa-solid fa-file-circle-plus me-2"></i> Submit Report</a>
    <a href="view_reports.php" class="<?= $current_page == 'view_reports.php' ? 'active' : ''; ?>"><i class="fa-solid fa-list me-2"></i> View My Reports</a>
    <a href="../logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a>
</div>

<!-- TOPBAR -->
<div class="topbar">
    <div class="topbar-right">
        <span class="date-display" id="dateDisplay"></span>
        <div class="profile-menu">
            <img src="<?= !empty($user['profile_pic']) ? '../uploads/profile/' . htmlspecialchars($user['profile_pic']) : '../assets/default_user.png'; ?>" 
                 class="profile-img" onclick="toggleProfileMenu()">
            <div class="profile-dropdown" id="profileDropdown">
                <a href="my_profile.php"><i class="fa-solid fa-user me-2"></i> My Profile</a>
                <a href="../logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="main-content py-4">

    <h2 class="mb-4 text-neon">Welcome, <?= htmlspecialchars($user['firstname']); ?> 👋</h2>

    <div class="row g-4 align-cards-start">

        <!-- Profile Card -->
        <div class="col-md-4">
            <div class="card bg-secondary text-center shadow border-neon flex-fill">
                <div class="card-body">
                    <img src="<?= !empty($user['profile_pic']) ? '../uploads/profile/' . htmlspecialchars($user['profile_pic']) : '../assets/default_user.png'; ?>" 
                         alt="Profile" class="rounded-circle mb-3" width="100" height="100">
                    <h5><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></h5>
                    <p class="mb-1 text-light"><?= htmlspecialchars($user['email']); ?></p>
                    <p class="text-light"><?= htmlspecialchars($user['mobile_number']); ?></p>
                </div>
            </div>
        </div>

        <!-- Reports Overview Card -->
        <div class="col-md-8">
            <div class="card bg-dark border-neon shadow">

                <h4 class="text-neon mb-3"><i class="fa-solid fa-clipboard-list"></i> Your Reports</h4>
                <h3 class="text text-center mb-4 text-light">View, submit, and track your reports.</h3>

                <div class="row g-4 text-center mb-4">

                    <!-- Total Reports -->
                    <div class="col-md-4">
                        <a href="view_reports.php" class="text-decoration-none">
                            <div class="card bg-dark text-center p-3 shadow border-neon">
                                <i class="fa-solid fa-file-lines fa-2x mb-2 text-neon"></i>
                                <h3 class="text-light"><?= $total_reports; ?></h3>
                                <div class="btn btn-info btn-sm mt-2 text-light w-100">Total Reports</div>
                            </div>
                        </a>
                    </div>

                    <!-- Pending Reports -->
                    <div class="col-md-4">
                        <a href="view_reports.php?filter=pending" class="text-decoration-none">
                            <div class="card bg-dark text-center p-3 shadow border-neon">
                                <i class="fa-solid fa-clock fa-2x mb-2 text-warning"></i>
                                <h3 class="text-light"><?= $pending_reports; ?></h3>
                                <div class="btn btn-warning btn-sm mt-2 text-dark w-100">Pending</div>
                            </div>
                        </a>
                    </div>

                    <!-- Approved Reports -->
                    <div class="col-md-4">
                        <a href="view_reports.php?filter=approved" class="text-decoration-none">
                            <div class="card bg-dark text-center p-3 shadow border-neon">
                                <i class="fa-solid fa-check-circle fa-2x mb-2 text-success"></i>
                                <h3 class="text-light"><?= $resolved_reports; ?></h3>
                                <div class="btn btn-success btn-sm mt-2 text-light w-100">Approved</div>
                            </div>
                        </a>
                    </div>

                </div>

                <div class="text-center py-3">
                    <a href="reports.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Create Report</a>
                </div>

            </div>
        </div>

    </div>
</div>

<script src="../assets/js/user.js"></script>
</body>
</html>
