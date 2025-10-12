<?php include '../db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | Padre Garcia Service Report System</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
</head>
<body>
  <button class="toggle-btn" onclick="toggleSidebar()">☰</button>

  <div class="container">
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
      <?php include 'includes/topbar.php'; ?>

      <div class="page-header">
        <h1>Admin Dashboard</h1>
        <p>Welcome back, Admin!</p>
      </div>

      <div class="summary-cards">
        <div class="card"><h3>Total Reports</h3><div class="count" id="totalReports">0</div></div>
        <div class="card"><h3>Pending</h3><div class="count" id="pendingReports">0</div></div>
        <div class="card"><h3>Completed</h3><div class="count" id="completedReports">0</div></div>
        <div class="card"><h3>Active Staff</h3><div class="count" id="activeStaff">0</div></div>
      </div>

      <div id="map" class="map-container"></div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="assets/js/script.js"></script>
</body>
</html>
