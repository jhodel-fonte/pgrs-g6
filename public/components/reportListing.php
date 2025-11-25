<?php 
$reports = require __DIR__ . '../../../admin/report_data.php';
?>
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

            <?php if (isset($reports) && isset($reports['success']) && $reports['success'] === false) : ?>
                <p class="text-center"><?= htmlspecialchars($reports['message']) ?></p>
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
                                <td><?= htmlspecialchars($r['user_id']) ?></td>
                                <td><?= htmlspecialchars($r['firstName'] . ' ' . $r['lastName']) ?></td>
                                <td><?= htmlspecialchars($r['name']) ?></td>
                                <td><?= htmlspecialchars($r['report_type']) ?></td>
                                <td>
                                    <span class="badge bg-<?= match($r['status']){
                                        'Approved'=>'success',
                                        'Pending'=>'warning',
                                        'Ongoing'=>'info',
                                        'Resolved'=>'primary',
                                        default=>'secondary'
                                    } ?>"><?= $r['status'] ?></span>
                                </td>
                                <td><?= $r['date_created'] ?></td>
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