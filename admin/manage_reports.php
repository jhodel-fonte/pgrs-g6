<?php
session_start();
require '../config/db.php';
require '../config/smsHandler.php';

// ✅ Check if admin is logged in
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$sms = new smsHandler();

// ✅ Handle Approve / Reject / Delete
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $reportId = intval($_GET['id']);

    $stmt = $pdo->prepare("
        SELECT r.*, u.firstname, u.lastname, u.mobile_number 
        FROM reports r
        JOIN users u ON r.user_id = u.id
        WHERE r.id = ?
    ");
    $stmt->execute([$reportId]);
    $report = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($report) {
        $number = $report['mobile_number'];
        $title = $report['title'];
        $fname = $report['firstname'];

        if ($action === 'approve') {
            $pdo->prepare("UPDATE reports SET status = 'Approved' WHERE id = ?")->execute([$reportId]);
            $sms->sendSms([
                'phoneNumber' => $number,
                'message' => "Hello $fname! Your report titled '$title' has been approved. The response team has been notified."
            ]);
        } elseif ($action === 'reject') {
            $sms->sendSms([
                'phoneNumber' => $number,
                'message' => "Hello $fname, your report titled '$title' has been rejected. Please review the details and try again."
            ]);
            $pdo->prepare("DELETE FROM reports WHERE id = ?")->execute([$reportId]);
        } elseif ($action === 'delete') {
            $pdo->prepare("DELETE FROM reports WHERE id = ?")->execute([$reportId]);
        }
    }

    header("Location: manage_reports.php?success=1");
    exit;
}

// ✅ Filter by status
$status = isset($_GET['status']) ? $_GET['status'] : 'All';

if ($status === 'All') {
    $stmt = $pdo->query("
        SELECT r.*, u.firstname, u.lastname, u.mobile_number 
        FROM reports r
        JOIN users u ON r.user_id = u.id
        ORDER BY r.date_submitted DESC
    ");
} else {
    $stmt = $pdo->prepare("
        SELECT r.*, u.firstname, u.lastname, u.mobile_number 
        FROM reports r
        JOIN users u ON r.user_id = u.id
        WHERE r.status = ?
        ORDER BY r.date_submitted DESC
    ");
    $stmt->execute([$status]);
}
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Required for active sidebar highlight
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Reports | Padre Garcia Reporting</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/admin.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="admin-bg">
<?php include '../admin/admin_sidebar.php'; ?>
<div class="main-content">
    <div class="container py-5">
        <div class="card-custom p-4 shadow-lg">
            <h3 class="text-neon text-center mb-4">Manage Reports</h3>

            <!-- Filter buttons -->
            <div class="d-flex justify-content-center mb-3 gap-2">
                <a href="?status=All" class="btn btn-outline-light <?= ($status == 'All') ? 'active' : '' ?>">All</a>
                <a href="?status=Pending" class="btn btn-outline-warning <?= ($status == 'Pending') ? 'active' : '' ?>">Pending</a>
                <a href="?status=Approved" class="btn btn-outline-success <?= ($status == 'Approved') ? 'active' : '' ?>">Approved</a>
                <a href="?status=Ongoing" class="btn btn-outline-info <?= ($status == 'Ongoing') ? 'active' : '' ?>">Ongoing</a>
                <a href="?status=Resolved" class="btn btn-outline-primary <?= ($status == 'Resolved') ? 'active' : '' ?>">Resolved</a>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success text-center">✅ Action completed successfully!</div>
            <?php endif; ?>

            <?php if (empty($reports)): ?>
                <p class="text-center">No reports found.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-dark table-striped text-center align-middle rounded-3 overflow-hidden">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($reports as $r): ?>
                            <tr>
                                <td><?= htmlspecialchars($r['id']); ?></td>
                                <td><?= htmlspecialchars($r['firstname'] . ' ' . $r['lastname']); ?></td>
                                <td><?= htmlspecialchars($r['title']); ?></td>
                                <td><?= htmlspecialchars($r['category']); ?></td>
                                <td>
                                    <?php
                                        $badge = match($r['status']) {
                                            'Approved' => 'success',
                                            'Pending' => 'warning',
                                            'Ongoing' => 'info',
                                            'Resolved' => 'primary',
                                            default => 'secondary',
                                        };
                                    ?>
                                    <span class="badge bg-<?= $badge; ?>"><?= htmlspecialchars($r['status']); ?></span>
                                </td>
                                <td><?= htmlspecialchars($r['date_submitted']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info view-details" 
                                            data-report='<?= json_encode($r); ?>'>
                                        View Details
                                    </button>
                                    <?php if ($r['status'] === 'Pending'): ?>
                                        <button onclick="confirmAction('approve', <?= $r['id']; ?>)" class="btn btn-success btn-sm">Approve</button>
                                        <button onclick="confirmAction('reject', <?= $r['id']; ?>)" class="btn btn-danger btn-sm">Reject</button>
                                    <?php else: ?>
                                        <button onclick="confirmAction('delete', <?= $r['id']; ?>)" class="btn btn-outline-danger btn-sm">Delete</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal: View Report Details -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header border-secondary">
        <h5 class="modal-title text-neon">Report Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Title:</strong> <span id="modalTitle"></span></p>
        <p><strong>Category:</strong> <span id="modalCategory"></span></p>
        <p><strong>Description:</strong> <span id="modalDescription"></span></p>
        <div class="text-center mb-3">
            <img id="modalImage" src="" class="img-fluid rounded shadow" style="max-height: 400px;">
        </div>
        <p><strong>Location:</strong> <span id="modalLocation"></span></p>
        <div id="mapContainer" class="rounded overflow-hidden" style="height: 300px;">
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/admin.js"></script>

</body>
</html>
