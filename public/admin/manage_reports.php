<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Reports | Padre Garcia Reporting</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/css/admin.css" rel="stylesheet">
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
                    </table>
                </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/admin.js"></script>

</body>
</html>
