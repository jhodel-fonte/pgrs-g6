<?php
// ----------------------------
// SAMPLE REPORTS DATA
// ----------------------------
$reports = 

$data_source_url = "http://localhost/pgrs-g6/request/reports.json";

try {
    $data = file_get_contents($data_source_url);

    if ($data === false) { 
        throw new Exception("Error: Could not retrieve data from the server.");
    }

    $response_data = json_decode($data, true); 

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Error: Failed to decode JSON. JSON Error: " . json_last_error_msg());
    }

    if ($response_data === null || !isset($response_data['success']) || $response_data['success'] !== true) {
        throw new Exception("Error: Invalid or unsuccessful response from data source.");
    }

    $reports = $response_data['data'] ?? [];

} catch (Exception $er) {
    // containlog('Error', $er->getMessage(), __DIR__, 'reportData.log');
}

$status = $_GET['status'] ?? 'All';
if ($status !== 'All') {
    $reports = array_filter($reports, fn($r) => isset($r['status']) && $r['status'] === $status);
}

// ----------------------------
// FILTER STATUS (from query string)
// ----------------------------
$status = $_GET['status'] ?? 'All';
if ($status !== 'All') {
    $reports = array_filter($reports, fn($r) => $r['status'] === $status);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Reports | Padre Garcia Reporting</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/admin.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php include '../admin/admin_sidebar.php'; ?>
<div class="main-content">
    <div class="container mt-4">
        <div class="card-custom p-4">
            <h3 class="text text-left mb-4">Manage Reports</h3>

            <!-- Filter buttons -->
            <div class="d-flex justify-content-center mb-3 gap-2 flex-wrap">
                <?php 
                $statuses = ['All','Pending','Approved','Ongoing','Resolved'];
                foreach ($statuses as $s): ?>
                    <a href="?status=<?= $s ?>" class="btn btn-outline-<?= match($s){
                        'Pending'=>'warning',
                        'Approved'=>'success',
                        'Ongoing'=>'info',
                        'Resolved'=>'primary',
                        default=>'dark'
                    } ?> <?= ($status==$s)?'active':'' ?>">
                        <?= $s ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Search box -->
            <div class="mb-3 d-flex justify-content-center">
                <input type="text" id="reportSearch" class="form w-50" placeholder="Search reports by user, title, or category...">
            </div>

            <?php if (empty($reports)): ?>
                <p class="text-center">No reports found.</p>
            <?php else: ?>
                <div class="table-responsive scroll-card">
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
                                <td><?= $r['user_id'] ?></td>
                                <td><?= $r['firstname'] . ' ' . $r['lastname'] ?></td>
                                <td><?= $r['name'] ?></td>
                                <td><?= htmlspecialchars($r['description']) ?></td>
                                <td>
                                    <span class="badge bg-<?= match($r['status']){
                                        'Approved'=>'success',
                                        'Pending'=>'warning',
                                        'Ongoing'=>'info',
                                        'Resolved'=>'primary',
                                        default=>'secondary'
                                    } ?>"><?= $r['status'] ?></span>
                                </td>
                                <td><?= $r['date_submitted'] ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info view-details" 
                                            data-report='<?= json_encode($r) ?>'>
                                        View Details
                                    </button>
                                    <?php if ($r['status']=='Pending'): ?>
                                        <button onclick="confirmAction('approve', <?= $r['id'] ?>)" class="btn btn-success btn-sm">Approve</button>
                                        <button onclick="confirmAction('reject', <?= $r['id'] ?>)" class="btn btn-danger btn-sm">Reject</button>
                                    <?php else: ?>
                                        <button onclick="confirmAction('delete', <?= $r['id'] ?>)" class="btn btn-outline-danger btn-sm">Delete</button>
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
        <h5 class="modal-title text">Report Details</h5>
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
        <div id="mapContainer" class="rounded overflow-hidden" style="height: 300px;"></div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/admin.js"></script>
</body>
</html>
