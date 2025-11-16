<?php
session_start();
require '../config/db.php';

// Redirect if not logged in or not a user
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'user') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $location = trim($_POST['location']);
    $latitude = trim($_POST['latitude']);
    $longitude = trim($_POST['longitude']);
    $image = '';

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../uploads/reports/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $image = time() . '_' . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $image;
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
    }

    // Save report
    $stmt = $pdo->prepare("INSERT INTO reports (user_id, title, description, category, image, location, latitude, longitude) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $title, $description, $category, $image, $location, $latitude, $longitude]);

    echo "<script>alert('✅ Report submitted successfully!'); window.location='reports.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Submit Report | Padre Garcia Reporting</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<style>
body { background: #0a0a0a; color: #fff; }
.text-neon { color: #00ffff; }
#map { height: 400px; border-radius: 10px; margin-bottom: 10px; }
.card-custom { background-color: #111; border: 1px solid #00ffff; padding: 25px; border-radius: 12px; }
.btn-neon {
    background-color: #fff;
    color: #000;
    font-weight: bold;
    border: none;
    transition: 0.3s;
}

</style>
</head>
<body>

<div class="container py-4">
    <!-- ✅ Back Button -->
    <div class="mb-3">
        <a href="dashboard.php" class="btn btn-neon">&larr; Back to Dashboard</a>
    </div>

<div class="container py-4">
    <h2 class="text-center text-neon mb-4"> Submit a Report</h2>
    <div class="card-custom">
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Report Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category" class="form-select" required>
                    <option value="">-- Select Category --</option>
                    <option value="Crime">Crime</option>
                    <option value="Fire">Fire</option>
                    <option value="Accident">Accident</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Upload Image (optional)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div class="mb-3">
                <label class="form-label">Your Location</label>
                <input type="text" id="location" name="location" class="form-control mt-2" placeholder="Your location will appear here" readonly required>
                <br>
                <div id="map"></div>
                <input type="hidden" id="latitude" name="latitude">
                <input type="hidden" id="longitude" name="longitude">
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">Submit Report</button>
        </form>
    </div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const defaultLocation = [13.8838, 121.2131]; // Padre Garcia default
    const map = L.map('map').setView(defaultLocation, 14);

    // Load map tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marker = L.marker(defaultLocation, { draggable: true }).addTo(map);

    // ✅ FIXED FUNCTION — works on localhost using proxy
    async function updateLocation(lat, lng) {
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;

        try {
            const url = `https://api.allorigins.win/get?url=${encodeURIComponent(
                `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`
            )}`;
            const res = await fetch(url);
            const data = await res.json();
            const json = JSON.parse(data.contents);
            const address = json.display_name || "Address not found";
            document.getElementById('location').value = address;
        } catch (error) {
            console.error("Address fetch error:", error);
            document.getElementById('location').value = "Unable to fetch address";
        }
    }

    // ✅ Detect user’s actual device location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            position => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                map.setView([lat, lng], 16);
                marker.setLatLng([lat, lng]);
                updateLocation(lat, lng);
            },
            error => {
                console.warn("⚠️ GPS not allowed, using default location.");
                updateLocation(defaultLocation[0], defaultLocation[1]);
            }
        );
    } else {
        alert("Geolocation not supported by your browser.");
        updateLocation(defaultLocation[0], defaultLocation[1]);
    }

    // Update when user drags marker
    marker.on('dragend', function(e) {
        const latLng = marker.getLatLng();
        updateLocation(latLng.lat, latLng.lng);
    });

    // Update when user clicks map
    map.on('click', function(e) {
        const { lat, lng } = e.latlng;
        marker.setLatLng([lat, lng]);
        updateLocation(lat, lng);
    });
});
</script>

</body>
</html>
