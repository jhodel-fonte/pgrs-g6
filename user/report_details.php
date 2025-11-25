<?php
session_start();
require '../config/db.php';

// Redirect if not logged in or not a user
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'user') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if report ID is provided
if (!isset($_GET['id'])) {
    header("Location: view_reports.php");
    exit;
}

$report_id = intval($_GET['id']);

// Fetch report and ensure it belongs to the logged-in user
$stmt = $pdo->prepare("SELECT * FROM reports WHERE id = ? AND user_id = ?");
$stmt->execute([$report_id, $user_id]);
$report = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$report) {
    echo "<script>alert('Report not found or access denied.'); window.location='view_reports.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Report Details | Padre Garcia Reporting</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
/* Base Styling */
/* Base Styling */
body {
    background-color: #fff;
    font-family: 'Poppins', sans-serif;
    color: #333;
    min-height: 100vh;
}

/* Container */
.container {
    max-width: 850px;
}

/* Header */
.report-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

h3 {
    font-weight: 600;
    color: #333;
}

/* Back Button — matches table View button style */
.btn-back {
    background-color: #0d6efd;
    color: #fff;
    font-weight: 600;
    border-radius: 6px;
    padding: 7px 16px;
    border: none;
    transition: 0.3s;
}

.btn-back:hover {
    background-color: #0b5ed7;
}

/* Card */
.card-modern {
    background-color: #393c3f;
    border: 1px solid #ffffffff; 
    border-radius: 12px;
    padding: 30px;
    margin-top: 20px;
}

/* Typography */
h4 {
    color: #ffffffff;
    font-weight: 600;
}

p, span, div {
    color: #ffffffff;
    line-height: 1.6;
}

.info-label {
    color: #4e89ff;
    font-weight: 500;
}

/* Image */
.report-image {
    width: 100%;
    max-height: 350px;
    object-fit: cover;
    border-radius: 10px;
    margin: 20px 0;
    border: 1px solid #ccc;
}

/* Status Container */
.status-container {
    background-color: #333;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #333;
    margin-top: 25px;
}

.status-title {
    font-weight: 600;
    font-size: 1.1rem;
    color: #4e89ff;
    margin-bottom: 15px;
}

/* Status icons */
.status-item {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
}

.status-light {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    border: 2px solid #333;
}

.status-light.pending { background: #ffffffff; }
.status-light.ongoing { background: #ffc107; }
.status-light.finished { background: #28a745; }

.status-label {
    font-size: 1rem;
    color: #ffffffff;
}

.active-status {
    font-weight: 600;
    color: #4e89ff;
}
</style>
</head>
<body>

<div class="container py-5">
    <div class="report-header mb-4">
        <h3 class="text-neon">📋 Report Details</h3>
        <a href="view_reports.php" class="btn btn-back">← Back</a>
    </div>

    <div class="card-modern">
        <h4><?= htmlspecialchars($report['title']); ?></h4>
        <p class="mb-2"><span class="info-label">Category:</span> <?= htmlspecialchars($report['category']); ?></p>

        <div class="mt-3">
            <span class="info-label">Description:</span>
            <p><?= nl2br(htmlspecialchars($report['description'])); ?></p>
        </div>

        <?php if (!empty($report['image'])): ?>
            <img src="../uploads/reports/<?= htmlspecialchars($report['image']); ?>" class="report-image" alt="Report Image">
        <?php endif; ?>

        <div class="mt-2"><span class="info-label">Location:</span> <?= htmlspecialchars($report['location']); ?></div>
        <div class="mt-2"><span class="info-label">Date Submitted:</span> <?= htmlspecialchars($report['created_at'] ?? 'N/A'); ?></div>

        <div class="status-container mt-4">
            <div class="status-title">Report Progress</div>

            <?php $status = strtolower($report['status']); ?>

            <div class="status-item">
                <div class="status-light <?= $status === 'pending' ? 'ongoing' : ($status !== 'pending' ? 'finished' : 'pending'); ?>"></div>
                <div class="status-label <?= $status === 'pending' ? 'active-status' : ''; ?>">Pending – Waiting for admin approval</div>
            </div>

            <div class="status-item">
                <div class="status-light <?= $status === 'ongoing' ? 'ongoing' : ($status === 'finished' ? 'finished' : 'pending'); ?>"></div>
                <div class="status-label <?= $status === 'ongoing' ? 'active-status' : ''; ?>">Ongoing – Report being handled</div>
            </div>

            <div class="status-item">
                <div class="status-light <?= $status === 'finished' ? 'finished' : 'pending'; ?>"></div>
                <div class="status-label <?= $status === 'finished' ? 'active-status' : ''; ?>">Finished – Report resolved</div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
