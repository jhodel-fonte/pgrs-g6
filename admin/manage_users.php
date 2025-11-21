<?php
// ----------------------------
// SAMPLE USERS DATA
// ----------------------------
$users = [
    [
        "id" => 1,
        "firstname" => "Jay Mark",
        "lastname" => "Rocero",
        "email" => "jay@example.com",
        "mobile_number" => "09171234567",
        "status" => "Pending",
        "gender" => "Male",
        "address" => "123 Main St, Barangay 1",
        "dob" => "1990-05-15",
        "profile_pic" => "profile_690ca862e4448.jpg",
        "id_doc" => "id1.jpg"
    ],
    [
        "id" => 2,
        "firstname" => "Tomoc",
        "lastname" => "Neil",
        "email" => "Tomne@example.com",
        "mobile_number" => "09181234567",
        "status" => "Approved",
        "gender" => "Female",
        "address" => "456 Riverside Rd",
        "dob" => "1992-08-20",
        "profile_pic" => "profile2.jpg",
        "id_doc" => "id2.jpg"
    ],
    [
        "id" => 3,
        "firstname" => "Mhiel",
        "lastname" => "Bisa",
        "email" => "Sakol@example.com",
        "mobile_number" => "09191234567",
        "status" => "Pending",
        "gender" => "Male",
        "address" => "789 Barangay Street",
        "dob" => "1988-12-02",
        "profile_pic" => "",
        "id_doc" => ""
    ],
     [
        "id" => 3,
        "firstname" => "Mhiel",
        "lastname" => "Bisa",
        "email" => "Sakol@example.com",
        "mobile_number" => "09191234567",
        "status" => "Pending",
        "gender" => "Male",
        "address" => "789 Barangay Street",
        "dob" => "1988-12-02",
        "profile_pic" => "",
        "id_doc" => ""
     ],
      [
        "id" => 3,
        "firstname" => "Mhiel",
        "lastname" => "Bisa",
        "email" => "Sakol@example.com",
        "mobile_number" => "09191234567",
        "status" => "Pending",
        "gender" => "Male",
        "address" => "789 Barangay Street",
        "dob" => "1988-12-02",
        "profile_pic" => "",
        "id_doc" => ""
      ],
       [
        "id" => 3,
        "firstname" => "Mhiel",
        "lastname" => "Bisa",
        "email" => "Sakol@example.com",
        "mobile_number" => "09191234567",
        "status" => "Pending",
        "gender" => "Male",
        "address" => "789 Barangay Street",
        "dob" => "1988-12-02",
        "profile_pic" => "",
        "id_doc" => ""
       ],
        [
        "id" => 3,
        "firstname" => "Mhiel",
        "lastname" => "Bisa",
        "email" => "Sakol@example.com",
        "mobile_number" => "09191234567",
        "status" => "Pending",
        "gender" => "Male",
        "address" => "789 Barangay Street",
        "dob" => "1988-12-02",
        "profile_pic" => "",
        "id_doc" => ""
        ],
         [
        "id" => 3,
        "firstname" => "Mhiel",
        "lastname" => "Bisa",
        "email" => "Sakol@example.com",
        "mobile_number" => "09191234567",
        "status" => "Pending",
        "gender" => "Male",
        "address" => "789 Barangay Street",
        "dob" => "1988-12-02",
        "profile_pic" => "",
        "id_doc" => ""
         ],
          [
        "id" => 3,
        "firstname" => "Mhiel",
        "lastname" => "Bisa",
        "email" => "Sakol@example.com",
        "mobile_number" => "09191234567",
        "status" => "Pending",
        "gender" => "Male",
        "address" => "789 Barangay Street",
        "dob" => "1988-12-02",
        "profile_pic" => "",
        "id_doc" => ""
    ]
    , [
        "id" => 3,
        "firstname" => "Mhiel",
        "lastname" => "Bisa",
        "email" => "Sakol@example.com",
        "mobile_number" => "09191234567",
        "status" => "Rejected",
        "gender" => "Male",
        "address" => "789 Barangay Street",
        "dob" => "1988-12-02",
        "profile_pic" => "",
        "id_doc" => ""
    ]
];

$status = $_GET['status'] ?? 'All';
if ($status !== 'All') {
    $users = array_filter($users, fn($r) => $r['status'] === $status);
}

// ----------------------------
// FLASH MESSAGE SIMULATION
// ----------------------------
session_start();
$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users | Padre Garcia Reporting</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="../admin/assets/admin.css">
</head>

<body>

<?php include '../admin/admin_sidebar.php'; ?>

<div class="main-content">
    <div class="container mt-4">
        <div class="card-custom p-4">

            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <h3 class="text">Manage User Requests</h3>
                <a href="create_team.php" class="btn btn-primary mt-2 mt-md-0"> Create Response Team</a>
            </div>

            <div class="d-flex justify-content-center mb-3 gap-2 flex-wrap">
                <?php 
                $statuses = ['All','Pending','Approved','Rejected'];
                foreach ($statuses as $s): ?>
                    <a href="?status=<?= $s ?>" class="btn btn-outline-<?= match($s){
                        'Pending'=>'warning',
                        'Approved'=>'success',
                        'Rejected'=>'danger',
                        default=>'dark'
                    } ?> <?= ($status==$s)?'active':'' ?>">
                        <?= $s ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- FLASH MESSAGE -->
            <?php if($message): ?>
                <div class="alert alert-<?= $message['type']; ?> alert-dismissible fade show" role="alert">
                    <?= $message['text']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

             <!-- Search box -->
            <div class="mb-3 d-flex justify-content-center">
                <input type="text" id="userSearch" class="form w-50" placeholder="Search reports by user name, email, or mobile number...">
            </div>

            <div class="table-responsive scroll-card">
            <table class="table table-dark table-hover text-center align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Details</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(empty($users)): ?>
                    <tr><td colspan="7">No users found.</td></tr>
                <?php else: foreach($users as $index => $u): ?>
                    <tr>
                        <td><?= $index + 1; ?></td>
                        <td><?= htmlspecialchars($u['firstname'] . ' ' . $u['lastname']); ?></td>
                        <td><?= htmlspecialchars($u['mobile_number']); ?></td>
                        <td><?= htmlspecialchars($u['email']); ?></td>
                        <td>
                            <span class="badge bg-<?= 
                                $u['status'] === 'Approved' ? 'success' : ($u['status'] === 'Rejected' ? 'danger' : 'warning text-dark') ?>"> <?= $u['status'] ?>
                            </span>
                        </td>

                        <!-- VIEW BUTTON -->
                        <td>
                            <button class="btn btn-sm btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#userModal<?= $u['id']; ?>">
                                View
                            </button>
                        </td>

                        <td>
                            <?php if($u['status'] === 'Pending'): ?>
                                <button class="btn btn-sm btn-success">Approve</button>
                                <button class="btn btn-sm btn-danger">Rejected</button>
                            <?php elseif($u['status'] === 'Approved'): ?>
                                <button class="btn btn-sm btn-danger">Delete</button>
                            <?php elseif($u['status'] === 'Rejected'): ?>
                                <button class="btn btn-sm btn-success">Approve</button>
                                <button class="btn btn-sm btn-danger">Delete</button>
                            <?php else: ?>
                                <span>No Action</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>

<!-- USER MODALS -->
<?php foreach($users as $u): ?>
<div class="modal fade" id="userModal<?= $u['id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-light">

            <div class="modal-header">
                <h5 class="modal-title">User Details - <?= htmlspecialchars($u['firstname'] . ' ' . $u['lastname']); ?></h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <?php if ($u['profile_pic']): ?>
                            <img src="../uploads/profile/<?= $u['profile_pic']; ?>"
                                 class="img-fluid rounded mb-3"
                                 style="max-height: 200px;">
                        <?php else: ?>
                            <p>No Profile Image</p>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-8">
                        <p><strong>Name:</strong> <?= $u['firstname'].' '.$u['lastname']; ?></p>
                        <p><strong>Email:</strong> <?= $u['email']; ?></p>
                        <p><strong>Mobile:</strong> <?= $u['mobile_number']; ?></p>
                        <p><strong>Gender:</strong> <?= $u['gender']; ?></p>
                        <p><strong>Address:</strong> <?= $u['address']; ?></p>
                        <p><strong>Date of Birth:</strong> <?= $u['dob']; ?></p>
                    </div>
                </div>

                <hr>

                <h5>ID Document</h5>
                <?php if ($u['id_doc']): ?>
                    <div class="text-center mt-2">
                        <img src="../uploads/id/<?= $u['id_doc']; ?>"
                             class="img-fluid rounded shadow"
                             style="max-height: 500px; object-fit: contain;">
                    </div>
                <?php else: ?>
                    <p>No ID Document Uploaded</p>
                <?php endif; ?>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<?php endforeach; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../admin/assets/admin.js"></script>
</body>
</html>
