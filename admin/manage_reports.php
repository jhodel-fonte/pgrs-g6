<?php

require_once __DIR__ .'../../src/utillities/log.php';

$data_source_url = "http://localhost/pgrs-g6/request/listReport.php"; 

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
    containlog('Error', $er->getMessage(), __DIR__, 'reportData.log');
}

$status = $_GET['status'] ?? 'All';
if ($status !== 'All') {
    $reports = array_filter($reports, fn($r) => isset($r['status']) && $r['status'] === $status);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Reports | Padre Garcia Reporting</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../admin/assets/admin.css" rel="stylesheet">
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
                    <a href="?status=<?= htmlspecialchars($s, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-<?= match($s){
                        'Pending'=>'warning',
                        'Approved'=>'success',
                        'Ongoing'=>'info',
                        'Resolved'=>'primary',
                        default=>'light'
                    } ?> <?= ($status==$s)?'active':'' ?>">
                        <?= htmlspecialchars($s, ENT_QUOTES, 'UTF-8') ?>
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
                        <?php foreach ($reports as $r): 
                            $reportId = htmlspecialchars($r['id'] ?? '');
                            $userName = htmlspecialchars(($r['user_full_name'] ?? ($r['firstName'] ?? '') . ' ' . ($r['lastName'] ?? '')) ?: 'Unknown');
                            $title = htmlspecialchars($r['name'] ?? '');
                            $category = htmlspecialchars($r['report_type'] ?? $r['ml_category'] ?? '');
                            $reportStatus = htmlspecialchars($r['status'] ?? '');
                            $dateSubmitted = htmlspecialchars($r['created_at'] ?? '');
                            // Format date if it's in ISO format
                            if ($dateSubmitted && strpos($dateSubmitted, 'T') !== false) {
                                $dateSubmitted = date('Y-m-d H:i:s', strtotime($dateSubmitted));
                            }
                        ?>
                            <tr>
                                <td><?= $reportId ?></td>
                                <td><?= $userName ?></td>
                                <td><?= $title ?></td>
                                <td><?= $category ?></td>
                                <td>
                                    <span class="badge bg-<?= match($reportStatus){
                                        'Approved'=>'success',
                                        'Pending'=>'warning',
                                        'Ongoing'=>'info',
                                        'Resolved'=>'primary',
                                        default=>'secondary'
                                    } ?>"><?= $reportStatus ?></span>
                                </td>
                                <td><?= $dateSubmitted ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#reportModal<?= $reportId ?>">
                                        View Details
                                    </button>
                                    <?php if ($reportStatus == 'Pending'): ?>
                                        <button onclick="confirmAction('approve', <?= $reportId ?>)" class="btn btn-success btn-sm">Approve</button>
                                        <button onclick="confirmAction('reject', <?= $reportId ?>)" class="btn btn-danger btn-sm">Reject</button>
                                    <?php else: ?>
                                        <button onclick="confirmAction('delete', <?= $reportId ?>)" class="btn btn-outline-danger btn-sm">Delete</button>
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

<!-- Generate modals for each report -->
    <?php foreach ($reports as $r): 
         $reportId = htmlspecialchars($r['id'] ?? '');
         $modalTitle = htmlspecialchars($r['name'] ?? 'Untitled Report');
         $modalCategory = htmlspecialchars($r['report_type'] ?? $r['ml_category'] ?? 'Unknown');
         $modalDescription = htmlspecialchars($r['description'] ?? 'No description provided.');
         $modalLocation = htmlspecialchars($r['location'] ?? 'Location not specified.');
         $modalPhoto = $r['photo'] ?? $r['image'] ?? null; 
         $modalImages = $r['images'] ?? []; // Images array from merged data
         $modalLat = $r['latitude'] ?? null;
         $modalLng = $r['longitude'] ?? null;
         $modalUser = htmlspecialchars(($r['user_full_name'] ?? ($r['firstName'] ?? '') . ' ' . ($r['lastName'] ?? '')) ?: 'Unknown');
         $modalDate = htmlspecialchars($r['created_at'] ?? '');
         if ($modalDate && strpos($modalDate, 'T') !== false) {
             $modalDate = date('Y-m-d H:i:s', strtotime($modalDate));
         }
     ?>
                        <div class="modal fade" id="reportModal<?= $reportId ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content bg-dark text-light">
                                    <div class="modal-header border-secondary">
                                        <h5 class="modal-title text-neon">Report Details</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <p><strong>Report ID:</strong> <?= $reportId ?></p>
                                                <p><strong>Title:</strong> <?= $modalTitle ?></p>
                                                <p><strong>Category:</strong> <?= $modalCategory ?></p>
                                                <p><strong>Status:</strong> 
                                                    <span class="badge bg-<?= match($r['status'] ?? ''){
                                                        'Approved'=>'success',
                                                        'Pending'=>'warning',
                                                        'Ongoing'=>'info',
                                                        'Resolved'=>'primary',
                                                        default=>'secondary'
                                                    } ?>"><?= htmlspecialchars($r['status'] ?? 'Unknown') ?></span>
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Submitted By:</strong> <?= $modalUser ?></p>
                                                <p><strong>User ID:</strong> <?= htmlspecialchars($r['user_id'] ?? 'N/A') ?></p>
                                                <p><strong>Date Submitted:</strong> <?= $modalDate ?></p>
                                                <?php if (isset($r['ml_category'])): ?>
                                                    <p><strong>ML Category:</strong> <?= htmlspecialchars($r['ml_category']) ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <hr class="border-secondary">
                                        <p><strong>Description:</strong></p>
                                        <p class="mb-3"><?= nl2br($modalDescription) ?></p>

                                        <p><strong>Location:</strong> <?= $modalLocation ?></p>
                                        
                                        <?php if ($modalLat && $modalLng): ?>
                                            <div class="rounded overflow-hidden mb-3" style="height: 300px;">
                                                <iframe
                                                    width="100%"
                                                    height="100%"
                                                    style="border:0;"
                                                    loading="lazy"
                                                    allowfullscreen
                                                    src="https://www.google.com/maps?q=<?= urlencode($modalLat) ?>,<?= urlencode($modalLng) ?>&z=14&output=embed">
                                                </iframe>
                                            </div>
                                        <?php else: ?>
                                            <div class="rounded overflow-hidden mb-3" style="height: 300px;">
                                                <p class="text-center text-muted p-3">No map location available.</p>
                                            </div>
                                        <?php endif; ?>

                                        <?php 
                                        // Display images from report_images table (merged into report object)
                                        if (!empty($modalImages)): ?>
                                            <p><strong>Report Images:</strong></p>
                                            <div class="row mb-3">
                                                <?php foreach ($modalImages as $img): 
                                                    $imagePath = $img['photo'] ?? $img['image_path'] ?? null;
                                                    if ($imagePath): ?>
                                                        <div class="col-md-4 mb-3">
                                                            <div class="text-center">
                                                                <img src="../assets/uploads/reports/<?= htmlspecialchars($imagePath) ?>"
                                                                     class="img-fluid rounded shadow" 
                                                                     style="max-height: 200px; width: 100%; object-fit: cover; cursor: pointer;"
                                                                     alt="Report image"
                                                                     onclick="window.open(this.src, '_blank')"
                                                                     onerror="this.style.display='none';">
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php 
                                        
                                        elseif ($modalPhoto): ?>
                                            <p><strong>Uploaded Image:</strong></p>
                                            <div class="text-center mb-3">
                                                <img src="../assets/uploads/reports/<?= htmlspecialchars($modalPhoto) ?>"
                                                     class="img-fluid rounded shadow" 
                                                     style="max-height: 400px;" 
                                                     alt="Report photo"
                                                     onclick="window.open(this.src, '_blank')"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                                <p class="text-muted text-center" style="display:none;">Image not available</p>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-center mb-3">
                                                <p class="text-muted">No images provided</p>
                                            </div>
                                        <?php endif; ?>


                                    </div>
                                </div>
                            </div>
                        </div>
    <?php endforeach; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../admin/assets/admin.js"></script>
</body>
</html>
