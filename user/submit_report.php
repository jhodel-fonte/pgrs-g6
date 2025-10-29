<?php
require_once '../config/config.php'; // PDO connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $report_type = trim($_POST['report_type']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $latitude = trim($_POST['latitude']);
    $longitude = trim($_POST['longitude']);
    $status = 'Pending';
    $photoPath = null;

    // File upload handler
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../uploads/";

        // Create folder if not exists
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = uniqid('report_') . "_" . basename($_FILES["photo"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
                $photoPath = "uploads/" . $fileName;
            } else {
                die("Error uploading the photo.");
            }
        } else {
            die("Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.");
        }
    }

    // Validation
    if (empty($name) || empty($report_type) || empty($description) || empty($location) || empty($latitude) || empty($longitude)) {
        die("All fields are required.");
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO reports (name, report_type, description, location, latitude, longitude, status, photo) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $report_type, $description, $location, $latitude, $longitude, $status, $photoPath]);

        header("Location: report.php?msg=success");
        exit;
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}
?>
