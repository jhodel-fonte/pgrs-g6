<?php 
session_start();
require_once '../config/config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = trim($_POST['name']);
    $report_type = trim($_POST['report_type']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $latitude = trim($_POST['latitude']);
    $longitude = trim($_POST['longitude']);
    $photo_path = null;

    // Photo Upload
    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $file_name = time() . "_" . basename($_FILES['photo']['name']);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed = ['jpg','jpeg','png','gif'];
        if (in_array($imageFileType, $allowed)) {
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                $photo_path = "uploads/" . $file_name;
            } else $error = "Failed to upload photo.";
        } else $error = "Only JPG, JPEG, PNG, and GIF files are allowed.";
    }

    if (empty($error)) {
        // --- CALL ML BACKEND ---
        $ml_result = null;
        try {
            $ch = curl_init('http://localhost:5000/analyze_report');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['description'=>$description]));
            $response = curl_exec($ch);
            curl_close($ch);
            $ml_result = json_decode($response, true);
        } catch (Exception $e) {
            $ml_result = null;
        }

        // Fallback if ML fails
        $ml_category = $ml_result['category'] ?? $report_type;
        $ml_summary = $ml_result['summary'] ?? '';
        $ml_legit = ($ml_result['legit_status'] ?? 'Legit') === 'Legit' ? 1 : 0;
        $ml_delay = ($ml_result['delay_status'] ?? 'On-Time') === 'On-Time' ? 0 : 1;

        try {
            $stmt = $pdo->prepare("
                INSERT INTO reports 
                (user_id, name, report_type, description, photo, location, latitude, longitude, status, ml_category, ml_summary, ml_legit, ml_delay) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending', ?, ?, ?, ?)
            ");
            $stmt->execute([
                $user_id, $name, $report_type, $description, $photo_path, $location, $latitude, $longitude,
                $ml_category, $ml_summary, $ml_legit, $ml_delay
            ]);
            $message = "Report submitted successfully!";
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>File Report | Unity Padre Garcia</title>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<style>
 * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Open Sans", sans-serif;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #004aad;
            color: #fff;
            padding: 20px;
        }

        .sidebar h2 {
            margin-bottom: 30px;
            font-size: 22px;
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            display: block;
            padding: 10px 15px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background-color: #003380;
        }

        /* Main content */
        main {
            flex: 1; padding: 40px;
        }
        h1 { font-size: 26px; margin-bottom: 20px; }
        .alert {
            margin-bottom: 15px; padding: 10px; border-radius: 6px; text-align: center;
        }
        .alert.success { background: #d4edda; color: #155724; }
        .alert.error { background: #f8d7da; color: #721c24; }

        .form-box {
            background: white; padding: 25px; border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); max-width: 700px;
        }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: 600; margin-bottom: 8px; }
        input, select, textarea {
            width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ccc;
        }
        button {
            background: #004aad; color: white; border: none;
            padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600;
        }
        button:hover { background: #003380; }

        #map {
            height: 300px;
            width: 100%;
            margin-bottom: 15px;
            border-radius: 8px;
        }
</style>
</head>
<body>
<div class="container">
<aside class="sidebar">
    <h2>Unity Padre Garcia</h2>
    <ul>
        <li><a href="user_dashboard.php" class="active">Dashboard</a></li>
        <li><a href="../user/report.php">Submit Report</a></li>
        <li><a href="../user/view.php">My Reports</a></li>
        <li><a href="../login/logout.php">Logout</a></li>
    </ul>
</aside>

<main>
<h1>File a Report</h1>
<?php if ($message): ?><div class="alert success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<div class="form-box">
<form method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($_SESSION['name']); ?>" readonly>
    </div>

    <div class="form-group">
        <label for="report_type">Report Type:</label>
        <select name="report_type" id="report_type" required>
            <option value="">Select Type</option>
            <option value="Fire">Fire</option>
            <option value="Rescue">Rescue</option>
            <option value="Accident">Accident</option>
            <option value="Others">Others</option>
        </select>
    </div>

    <div class="form-group">
        <label for="description">Description:</label>
        <textarea name="description" id="description" rows="5" placeholder="Describe your report..." required></textarea>
    </div>

    <div class="form-group">
        <label for="photo">Upload Photo (optional):</label>
        <input type="file" name="photo" id="photo" accept="image/*">
    </div>

    <div class="form-group">
        <label for="location">Location:</label>
        <input type="text" id="location" name="location" readonly required>
    </div>
    <input type="hidden" id="latitude" name="latitude" required>
    <input type="hidden" id="longitude" name="longitude" required>

    <div id="map"></div>

    <button type="submit">Submit Report</button>
</form>
</div>
</main>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const map = L.map('map').setView([13.9833, 121.1333], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: 'Map data © OpenStreetMap contributors' }).addTo(map);
    let marker;
    map.on('click', function(e) {
        const {lat,lng} = e.latlng;
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        document.getElementById('location').value = `Lat: ${lat.toFixed(5)}, Lng: ${lng.toFixed(5)}`;
        if (marker) marker.remove();
        marker = L.marker([lat,lng]).addTo(map);
    });
});
</script>
</body>
</html>
