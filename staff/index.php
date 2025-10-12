<?php
include '../db_connect.php';

// Fetch counts
$total_result = $conn->query("SELECT COUNT(*) as total FROM reports");
$total = $total_result->fetch_assoc()['total'];

$pending_result = $conn->query("SELECT COUNT(*) as pending FROM reports WHERE status = 'Pending'");
$pending = $pending_result->fetch_assoc()['pending'];

$resolved_result = $conn->query("SELECT COUNT(*) as resolved FROM reports WHERE status = 'Resolved'");
$resolved = $resolved_result->fetch_assoc()['resolved'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unity Padre Garcia Service Report System - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
     <div class="container">
    <?php include 'sidebar.php'; ?>


        <!-- Main Content -->
        <main class="main-content">
            <header class="page-header">
                <h1>Dashboard</h1>
                <p>Welcome to the Unity Padre Garcia Service Report System</p>
            </header>

            <div class="summary-cards">
                <div class="card">
                    <h3>Total Reports</h3>
                    <p class="count"><?php echo $total; ?></p>
                </div>
                <div class="card">
                    <h3>Pending Reports</h3>
                    <p class="count"><?php echo $pending; ?></p>
                </div>
                <div class="card">
                    <h3>Resolved Reports</h3>
                    <p class="count"><?php echo $resolved; ?></p>
                </div>
            </div>
        </main>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>