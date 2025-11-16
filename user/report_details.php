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
body {
    background: linear-gradient(180deg, #0a0a0a, #1a1a1a);
    font-family: 'Poppins', sans-serif;
    color: #e5e5e5;
    min-height: 100vh;
}

/* Container Layout */
.container {
    max-width: 850px;
}

/* Header */
.report-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
h3.text-neon {
    font-weight: 600;
    color: #00eaff;
    letter-spacing: 1px;
    text-shadow: 0 0 10px #00eaff, 0 0 25px rgba(0,234,255,0.4);
}

/* Back Button */
.btn-back {
    background: linear-gradient(90deg, #00eaff, #00ffa1);
    color: #000;
    font-weight: 600;
    border: none;
    border-radius: 10px;
    padding: 8px 18px;
    transition: 0.3s;
}
.btn-back:hover {
    background: linear-gradient(90deg, #00ffa1, #00eaff);
    transform: scale(1.05);
}

/* Card Container */
.card-modern {
    background: rgba(25, 25, 25, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 20px;
    padding: 35px 40px;
    margin-top: 20px;
    backdrop-filter: blur(8px);
    box-shadow: 0 0 35px rgba(0, 234, 255, 0.05);
    transition: all 0.3s ease;
}
.card-modern:hover {
    box-shadow: 0 0 50px rgba(0, 234, 255, 0.15);
}

/* Typography */
h4 {
    color: #ffffff;
    font-weight: 600;
}
p, span, div {
    color: #cfd3d6;
    line-height: 1.6;
}
.info-label {
    color: #00eaff;
    font-weight: 500;
}

/* Image Styling */
.report-image {
    width: 100%;
    max-height: 380px;
    object-fit: cover;
    border-radius: 14px;
    margin: 20px 0;
    border: 1px solid rgba(0, 234, 255, 0.2);
    transition: 0.4s;
}
.report-image:hover {
    transform: scale(1.01);
    box-shadow: 0 0 20px rgba(0, 234, 255, 0.25);
}

/* Status Section */
.status-container {
    background: rgba(15, 15, 15, 0.95);
    border-radius: 16px;
    padding: 25px;
    border: 1px solid rgba(255,255,255,0.05);
    margin-top: 25px;
}
.status-title {
    font-weight: 600;
    font-size: 1.1rem;
    color: #00eaff;
    margin-bottom: 15px;
    text-shadow: 0 0 10px rgba(0,234,255,0.4);
}
.status-item {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
    transition: 0.3s;
}
.status-item:hover {
    transform: translateX(5px);
}
.status-light {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: 2px solid #333;
    transition: 0.4s;
}
.status-light.pending { background: #555; }
.status-light.ongoing {
    background: #ffcc00;
    box-shadow: 0 0 12px #ffcc00;
    animation: glowYellow 1.8s infinite;
}
.status-light.finished {
    background: #00ff9d;
    box-shadow: 0 0 14px #00ff9d;
    animation: glowGreen 1.8s infinite;
}
.status-label {
    font-size: 1rem;
    color: #ccc;
}
.active-status {
    font-weight: 600;
    color: #00eaff;
}

/* Glow Animations */
@keyframes glowYellow {
    0%, 100% { box-shadow: 0 0 10px #ffcc00; }
    50% { box-shadow: 0 0 20px #ffcc00; }
}
@keyframes glowGreen {
    0%, 100% { box-shadow: 0 0 10px #00ff9d; }
    50% { box-shadow: 0 0 20px #00ff9d; }
}

/* Responsive */
@media (max-width: 576px) {
    .card-modern {
        padding: 25px 20px;
    }
    .btn-back {
        font-size: 0.9rem;
    }
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
