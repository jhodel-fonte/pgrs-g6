<?php
$message = '';
if (isset($_GET['msg']) && $_GET['msg'] == 'success') {
    $message = '<div class="alert alert-success">Report submitted successfully!</div>';
}
?>
<?php if (!empty($row['photo'])): ?>
    <img src="../<?php echo htmlspecialchars($row['photo']); ?>" alt="Report Photo" width="200">
<?php else: ?>
    <p>No photo uploaded</p>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unity Padre Garcia Service Report System - File Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">

    
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<body>
     <div class="container">
     <?php include 'sidebar.php'; ?>


        <!-- Main Content -->
        <main class="main-content">
            <header class="page-header">
                <h1>File a Report</h1>
            </header>

            <?php echo $message; ?>

           <form id="reportForm" class="report-form" action="submit_report.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
    </div>

    <div class="form-group">
        <label for="report_type">Report Type:</label>
        <select id="report_type" name="report_type" required>
            <option value="">Select Type</option>
            <option value="Fire">Fire</option>
            <option value="Rescue">Rescue</option>
            <option value="Accident">Accident</option>
            <option value="Others">Others</option>
        </select>
    </div>

    <div class="form-group">
        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea>
    </div>

    <!-- mag a-upload ng file -->
    <div class="form-group">
        <label for="photo">Upload Photo (optional):</label>
        <input type="file" id="photo" name="photo" accept="image/*">
    </div>

    <div class="form-group">
        <label for="location">Location:</label>
        <input type="text" id="location" name="location" readonly required>
    </div>

    <input type="hidden" id="latitude" name="latitude" required>
    <input type="hidden" id="longitude" name="longitude" required>
    <input type="hidden" name="status" value="Pending">

    <div id="map" class="map-container"></div>

    <button type="submit" class="btn btn-primary">Submit Report</button>
</form>

        </main>
    </div>
    
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

   <script src="assets/js/script.js"></script>
</body>
</html>
