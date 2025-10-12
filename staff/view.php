<?php
include '../db_connect.php';
$result = $conn->query("SELECT * FROM reports ORDER BY date_reported DESC");
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reports - Unity Padre Garcia Service Report System</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<body>
    <div class="container">
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <header class="page-header">
                <h1>View Reports</h1>
                <p>Track the status of submitted reports in real time</p>
            </header>

            <div class="report-list">
                <?php while ($row = $result->fetch_assoc()): ?>
                <div class="report-card">
                    <div class="report-header">
                        <h2><?= htmlspecialchars($row['report_type']) ?> Report</h2>
                        <?php
                            $statusClass = '';
                            if ($row['status'] == 'Pending') $statusClass = 'status-pending';
                            elseif ($row['status'] == 'In Progress') $statusClass = 'status-progress';
                            elseif ($row['status'] == 'Completed') $statusClass = 'status-completed';
                        ?>
                        <span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($row['status']) ?></span>
                    </div>

                    <div class="report-body">
                        <p><strong>Name:</strong> <?= htmlspecialchars($row['name']) ?></p>
                        <p><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
                        <p><strong>Description:</strong> <?= htmlspecialchars($row['description']) ?></p>

                        <?php 
                       
                        if (!empty($row['photo']) && file_exists("../" . $row['photo'])): ?>
                            <div class="report-photo">
                                <img src="../<?= htmlspecialchars($row['photo']); ?>" alt="Report Photo" class="report-image">
                            </div>
                        <?php else: ?>
                            <p class="no-photo">No photo uploaded</p>
                        <?php endif; ?>

                        <p class="report-meta">
                            <i class="fa-regular fa-clock"></i> Reported on <?= date('Y-m-d H:i', strtotime($row['date_reported'])) ?>
                        </p>
                    </div>

                    <!-- Map -->
                    <div id="map-<?= $row['id'] ?>" 
                        class="map-container" 
                        data-lat="<?= htmlspecialchars($row['latitude']) ?>" 
                        data-lng="<?= htmlspecialchars($row['longitude']) ?>">
                    </div>

                    <!-- Status tracker -->
                    <div class="status-tracker">
                        <div class="step <?= ($row['status'] == 'Pending' || $row['status'] == 'In Progress' || $row['status'] == 'Completed') ? 'active' : '' ?>">
                            <i class="fa-solid fa-file-circle-exclamation"></i>
                            <p>Pending</p>
                        </div>
                        <div class="step <?= ($row['status'] == 'In Progress' || $row['status'] == 'Completed') ? 'active' : '' ?>">
                            <i class="fa-solid fa-gear"></i>
                            <p>In Progress</p>
                        </div>
                        <div class="step <?= ($row['status'] == 'Completed') ? 'active' : '' ?>">
                            <i class="fa-solid fa-circle-check"></i>
                            <p>Completed</p>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </main>
    </div>

    <!-- Scripts -->
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
                        .bindPopup("Reported location")
                        .openPopup();
                } else {
                    mapEl.innerHTML = "<p style='text-align:center;color:#999;'>Location data unavailable</p>";
                }
            });
        });
    </script>
</body>
</html>
