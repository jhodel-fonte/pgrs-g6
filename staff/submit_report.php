<?php
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $report_type = mysqli_real_escape_string($conn, $_POST['report_type']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $latitude = mysqli_real_escape_string($conn, $_POST['latitude']);
    $longitude = mysqli_real_escape_string($conn, $_POST['longitude']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $photoPath = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../uploads/";

        // mag c-create ng foledr kung wala pa
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Secure filename
        $fileName = uniqid() . "_" . basename($_FILES["photo"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        // Allowed image types
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

    // Insert into database
    $sql = "INSERT INTO reports (name, report_type, description, location, latitude, longitude, status, photo) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssddss", $name, $report_type, $description, $location, $latitude, $longitude, $status, $photoPath);

    if ($stmt->execute()) {
        header("Location: report.php?msg=success");
        exit();
    } else {
        die("Error: " . $conn->error);
    }

    $stmt->close();
}

$conn->close();
?>
