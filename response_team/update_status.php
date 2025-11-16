<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'response_team') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = intval($_GET['id']);

// Fetch report
$stmt = $pdo->prepare("SELECT * FROM reports WHERE id = ?");
$stmt->execute([$id]);
$report = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$report) {
    die("Report not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newStatus = $_POST['status'];

    $update = $pdo->prepare("UPDATE reports SET status = ? WHERE id = ?");
    $update->execute([$newStatus, $id]);

    header("Location: dashboard.php?updated=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Update Report Status</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/rteam.css" rel="stylesheet">
</head>
<body>

<?php include 'rt_sidebar.php'; ?>

<div class="main-content p-4">
    <h3 class="text-light mb-3">Update Status</h3>

    <div class="card p-4 bg-dark text-light shadow">
        <p><strong>Type:</strong> <?= $report['report_type']; ?></p>
        <p><strong>Description:</strong> <?= $report['description']; ?></p>

        <form method="POST">
            <label class="form-label text-light">Select Status</label>
            <select name="status" class="form-control mb-3" required>
                <option value="Responded">Responded</option>
                <option value="Completed">Completed</option>
            </select>

            <button class="btn btn-primary w-100">Update Status</button>
        </form>
    </div>
</div>

</body>
</html>
