<?php
// ----------------------------
// SAMPLE REPORTS DATA
// ----------------------------
$reports = [
    [
        "id" => 1,
        "firstname" => "Jay Mark",
        "lastname" => "Rocero",
        "title" => "Fire in Barangay",
        "category" => "Fire",
        "status" => "Pending",
        "date_submitted" => "2025-11-01",
        "description" => "Fire reported near residential area",
        "image" => "sample1.jpg",
        "location" => "Barangay 1",
        "latitude" => 13.7563,
        "longitude" => 121.0583
    ],
    [
        "id" => 2,
        "firstname" => "Neil",
        "lastname" => "Tomoc",
        "title" => "Road Accident",
        "category" => "Accident",
        "status" => "Approved",
        "date_submitted" => "2025-11-05",
        "description" => "Car collision reported",
        "image" => "sample2.jpg",
        "location" => "Main St.",
        "latitude" => 13.7580,
        "longitude" => 121.0600
    ],
    [
        "id" => 3,
        "firstname" => "miel",
        "lastname" => "Na Bisaya",
        "title" => "Flooding",
        "category" => "Natural Disaster",
        "status" => "Ongoing",
        "date_submitted" => "2025-11-10",
        "description" => "Flooding reported in low-lying areas",
        "image" => "sample3.jpg",
        "location" => "Riverside",
        "latitude" => 13.7550,
        "longitude" => 121.0620
    ]
];

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
            <div class="d-flex justify-content-center mb-3 gap-2 flex-wrap">
                <?php 
                $statuses = ['All','Pending','Approved','Ongoing','Resolved'];
                foreach ($statuses as $s): ?>
                    <a href="?status=<?= $s ?>" class="btn btn-outline-<?= match($s){
                        'Pending'=>'warning',
                        'Approved'=>'success',
                        'Ongoing'=>'info',
                        'Resolved'=>'primary',
                        default=>'light'
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
                                <td><?= $r['id'] ?></td>
                                <td><?= $r['firstname'] . ' ' . $r['lastname'] ?></td>
                                <td><?= $r['title'] ?></td>
                                <td><?= $r['category'] ?></td>
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
        <div id="mapContainer" class="rounded overflow-hidden" style="height: 300px;"></div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/admin.js"></script>
</body>
</html>
