<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection once
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
    if (strtolower($row['status']) === 'resolved' || strtolower($row['status']) === 'approved') $resolved_reports = $row['count'];
}

// Set current page for active sidebar link
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Dashboard | Padre Garcia Reporting</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/user.css">
</head>
<body>

<?php include 'user_sidebar.php'; ?>

<!-- Main content -->
<div class="main-content container py-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-neon">Welcome, <?= htmlspecialchars($user['firstname']); ?> 👋</h2>
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

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-neon"><i class="fa-solid fa-clipboard-list"></i> Your Reports</h4>
                </div>

                <p class="text-light">View, submit, and track your submitted reports here.</p>

                <!-- CLICKABLE CARDS -->
                <div class="row text-center mb-4    ">

                    <!-- Total Reports -->
                    <div class="col-md-4">
                        <a href="view_reports.php" class="text-decoration-none">
                            <div class="report-stat card-click p-3 rounded">
                                <h3><?= $total_reports; ?></h3>
                                <p>Reports</p>
                            </div>
                        </a>
                    </div>

                    <!-- Pending Reports -->
                    <div class="col-md-4">
                        <a href="view_reports.php?filter=pending" class="text-decoration-none">
                            <div class="report-stat card-click p-3 rounded">
                                <h3><?= $pending_reports; ?></h3>
                                <p>Pending</p>
                            </div>
                        </a>
                    </div>

                    <!-- Approved Reports -->
                    <div class="col-md-4">
                        <a href="reports.php?filter=approved" class="text-decoration-none">
                            <div class="report-stat card-click p-3 rounded">
                                <h3><?= $resolved_reports; ?></h3>
                                <p>Approved</p>
                            </div>
                        </a>
                    </div>

                </div>

                <!-- Create Report Button -->
                <div class="text-center mt-3">
                    <a href="reports.php" class="btn btn-primary">
                        <i class="fa-solid fa-plus"></i> Create Report
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- JS -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const btn = document.querySelector(".dropdown-btn");
    const content = document.querySelector(".dropdown-content");

    if (btn && content) {   // <-- prevents errors
        btn.addEventListener("click", () => {
            content.style.display =
                content.style.display === "block" ? "none" : "block";
        });
    }
});
</script>


<script src="https://kit.fontawesome.com/a2e0e6ad15.js" crossorigin="anonymous"></script>
<script src="../assets/js/user.js"></script>

</body>
</html>
