<?php
include '../db_connect.php';

$sql = "SELECT id, service_type, location, latitude, longitude, photo, status, date_reported FROM reports";
$result = $conn->query($sql);

$reports = [];
while ($row = $result->fetch_assoc()) {
  $reports[] = $row;
}

echo json_encode($reports);
?>
