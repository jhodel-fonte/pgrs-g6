<?php include '../db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Service Reports | Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <button class="toggle-btn" onclick="toggleSidebar()">☰</button>

  <div class="container">
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
      <?php include 'includes/topbar.php'; ?>

      <div class="page-header">
        <h1>Service Reports</h1>
        <p>View and manage submitted reports with photos and map preview.</p>
      </div>

      <div class="table-container">
        <table id="reportTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Service Type</th>
              <th>Location / Map</th>
              <th>Photo</th>
              <th>Status</th>
              <th>Date Reported</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="assets/js/reports.js"></script>
</body>
</html>
