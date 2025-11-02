<?php
// require_once '../config/config.php'; // PDO connection
require_once __DIR__ .'../../../src/data/config.php';

// Fetch reports ordered by latest first
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
    <title>My Reports - Unity Padre Garcia Service Report System</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        body {
            margin: 0;
            font-family: "Open Sans", sans-serif;
            background-color: #f5f9ff;
        }
        .container {
            display: flex;
            min-height: 100vh;
        }
        /* Sidebar */
        .sidebar {
            width: 250px;
            background: #004aad;
            color: white;
            padding: 20px;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 15px 0;
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            display: block;
            padding: 10px;
            border-radius: 8px;
            transition: background 0.3s;
        }
        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background: #1e64d8;
        }

        /* Main content */
        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }
        .page-header h1 {
            color: #004aad;
            margin-bottom: 5px;
        }
        .page-header p {
            color: #555;
            margin-bottom: 20px;
        }

        /* Report Cards */
        .report-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .report-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .report-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .report-header h2 {
            margin: 0;
            color: #004aad;
            font-size: 20px;
        }

        /* Status Badges */
        .status-badge, .approval-badge {
            padding: 6px 12px;
            border-radius: 20px;
            color: white;
            font-size: 13px;
            font-weight: 600;
            margin-left: 10px;
        }
        .status-pending { background: #f0ad4e; }
        .status-progress { background: #0275d8; }
        .status-completed { background: #5cb85c; }

        .pending-approval { background: #f0ad4e; }
        .approved { background: #5cb85c; }
        .disapproved { background: #d9534f; }

        .report-body p {
            margin: 6px 0;
            color: #333;
        }
        .report-photo {
            margin-top: 10px;
        }
        .report-image {
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
            margin-top: 10px;
        }
        .no-photo {
            font-style: italic;
            color: #888;
        }
        .report-meta {
            margin-top: 10px;
            color: #666;
            font-size: 14px;
        }

        /* Map container */
        .map-container {
            height: 200px;
            border-radius: 10px;
            margin-top: 10px;
        }

        /* Shopee-style Status Tracker */
        .status-tracker {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
        .status-tracker .step {
            text-align: center;
            flex: 1;
            position: relative;
        }
        .status-tracker .step:before {
            content: '';
            position: absolute;
            top: 20px;
            left: 50%;
            width: 100%;
            height: 3px;
            background: #d3e3ff;
            z-index: -1;
            transform: translateX(-50%);
        }
        .status-tracker .step.active:before {
            background: #004aad;
        }
        .status-tracker .step i {
            font-size: 22px;
            background: #e9f2ff;
            color: #004aad;
            border-radius: 50%;
            padding: 10px;
            margin-bottom: 5px;
        }
        .status-tracker .step.active i {
            background: #004aad;
            color: white;
        }
        .status-tracker .step p {
            font-size: 13px;
            color: #333;
            font-weight: 600;
        }
    </style>
</head>
<body>
<div class="container">
    <aside class="sidebar">
        <h2>Unity Padre Garcia</h2>
        <ul>
            <li><a href="user_dashboard.php">Dashboard</a></li>
            <li><a href="../user/report.php">Submit Report</a></li>
            <li><a href="../user/view_reports.php" class="active">My Reports</a></li>
            <li><a href="../login/logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="page-header">
            <h1>My Reports</h1>
            <p>Track the progress and approval status of your submitted reports</p>
        </header>

        <div class="report-list">
            <?php if (empty($reports)): ?>
                <p style="text-align:center; color:#777;">No reports have been submitted yet.</p>
            <?php else: ?>
                <?php foreach ($reports as $row): ?>
                    <div class="report-card">
                        <div class="report-header">
                            <h2><?= htmlspecialchars($row['report_type']) ?> Report</h2>
                            <?php
                                $statusClass = $row['status'] == 'Pending' ? 'status-pending' : 
                                               ($row['status'] == 'In Progress' ? 'status-progress' : 'status-completed');
                                $approvalClass = !isset($row['approval_status']) || $row['approval_status']=='Pending' ? 'pending-approval' :
                                                 ($row['approval_status']=='Approved' ? 'approved' : 'disapproved');
                            ?>
                            <span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($row['status']) ?></span>
                            <span class="approval-badge <?= $approvalClass ?>"><?= htmlspecialchars($row['approval_status'] ?? 'Pending') ?></span>
                        </div>

                        <div class="report-body">
                            <p><strong>Name:</strong> <?= htmlspecialchars($row['name']) ?></p>
                            <p><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
                            <p><strong>Description:</strong> <?= htmlspecialchars($row['description']) ?></p>

                            <?php if (!empty($row['photo']) && file_exists("../" . $row['photo'])): ?>
                                <div class="report-photo">
                                    <img src="../<?= htmlspecialchars($row['photo']); ?>" alt="Report Photo" class="report-image">
                                </div>
                            <?php else: ?>
                                <p class="no-photo">No photo uploaded</p>
                            <?php endif; ?>

                            <p class="report-meta">
                                <i class="fa-regular fa-clock"></i>
                                Reported on <?= date('Y-m-d H:i', strtotime($row['created_at'])) ?>
                            </p>
                        </div>

                        <div id="map-<?= $row['id'] ?>" 
                             class="map-container" 
                             data-lat="<?= htmlspecialchars($row['latitude']) ?>" 
                             data-lng="<?= htmlspecialchars($row['longitude']) ?>">
                        </div>

                        <div class="status-tracker">
                            <div class="step <?= ($row['status'] == 'Pending' || $row['status'] == 'In Progress' || $row['status'] == 'Resolved') ? 'active' : '' ?>">
                                <i class="fa-solid fa-file-circle-exclamation"></i>
                                <p>Pending</p>
                            </div>
                            <div class="step <?= ($row['status'] == 'In Progress' || $row['status'] == 'Resolved') ? 'active' : '' ?>">
                                <i class="fa-solid fa-gear"></i>
                                <p>In Progress</p>
                            </div>
                            <div class="step <?= ($row['status'] == 'Resolved') ? 'active' : '' ?>">
                                <i class="fa-solid fa-circle-check"></i>
                                <p>Resolved</p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const maps = document.querySelectorAll('.map-container');
    maps.forEach(mapEl => {
        const lat = parseFloat(mapEl.dataset.lat);
        const lng = parseFloat(mapEl.dataset.lng);

        if (!isNaN(lat) && !isNaN(lng)) {
            const map = L.map(mapEl).setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
            L.marker([lat, lng]).addTo(map)
                .bindPopup("Reported Location")
                .openPopup();
        } else {
            mapEl.innerHTML = "<p style='text-align:center;color:#999;'>Location data unavailable</p>";
        }
    });
});
</script>
</body>
</html>
