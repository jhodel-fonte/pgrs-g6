<?php
session_start();
require '../config/db.php';
require '../config/smsHandler.php';

// Only allow admin access
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ----------------------------
// APPROVE USER
// ----------------------------
if (isset($_GET['approve'])) {
    $userId = intval($_GET['approve']);

    $stmt = $pdo->prepare("UPDATE users SET status = 'Approved', is_approved = 1 WHERE id = ?");
    $stmt->execute([$userId]);

    $stmtUser = $pdo->prepare("SELECT firstname, mobile_number FROM users WHERE id = ?");
    $stmtUser->execute([$userId]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    $sms = new smsHandler($pdo);
    $sms->sendSms([
        'user_id' => $userId,
        'phoneNumber' => $user['mobile_number'],
        'message' => "Hello {$user['firstname']}, your Padre Garcia Service Report System account has been approved. You can now log in."
    ]);

    // ✅ Flash message
    $_SESSION['message'] = ['type' => 'success', 'text' => 'User approved and SMS sent!'];

    header("Location: manage_users.php");
    exit;
}

// ----------------------------
// REJECT USER
// ----------------------------
if (isset($_GET['reject'])) {
    $userId = intval($_GET['reject']);

    $stmtUser = $pdo->prepare("SELECT firstname, mobile_number FROM users WHERE id = ?");
    $stmtUser->execute([$userId]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    $sms = new smsHandler($pdo);
    $sms->sendSms([
        'user_id' => $userId,
        'phoneNumber' => $user['mobile_number'],
        'message' => "Hello {$user['firstname']}, your Padre Garcia Service Report System registration has been rejected."
    ]);

    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$userId]);

    // ❌ Flash message
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'User rejected and deleted.'];

    header("Location: manage_users.php");
    exit;
}

// ----------------------------
// FETCH USERS
// ----------------------------
$stmt = $pdo->query("
    SELECT * FROM users
    WHERE role = 'user'
    ORDER BY 
        CASE WHEN status = 'Pending' THEN 1 ELSE 2 END,
        id DESC
");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users | Padre Garcia Reporting</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/admin.css" rel="stylesheet">
</head>

<body>
<div class="admin-bg">

<?php include '../admin/admin_sidebar.php'; ?>

<div class="main-content">
    <div class="container mt-4">
        <div class="card-custom p-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="text">Manage User Requests</h3>
                <a href="create_team.php" class="btn btn-outline-info">➕ Create Response Team</a>
            </div>

            <!-- FLASH MESSAGE -->
            <?php if(isset($_SESSION['message'])): ?>
                <div class="alert alert-<?= $_SESSION['message']['type']; ?> alert-dismissible fade show" role="alert">
                    <?= $_SESSION['message']['text']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

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
                            <?php if($u['status'] === 'Approved'): ?>
                                <span class="badge bg-success">Approved</span>
                            <?php elseif($u['status'] === 'Pending'): ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?= htmlspecialchars($u['status']); ?></span>
                            <?php endif; ?>
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
                                <a href="?approve=<?= $u['id']; ?>" class="btn btn-sm btn-success">Approve</a>
                                <a href="?reject=<?= $u['id']; ?>" class="btn btn-sm btn-danger">Reject</a>
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

<!-- ====================================================== -->
<!-- USER DETAILS MODALS WITH ID SHOWN INSIDE -->
<!-- ====================================================== -->

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
                    <!-- Profile Picture -->
                    <div class="col-md-4 text-center">
                        <?php if ($u['profile_pic']): ?>
                            <img src="../uploads/profile/<?= $u['profile_pic']; ?>"
                                 class="img-fluid rounded mb-3"
                                 style="max-height: 200px;">
                        <?php else: ?>
                            <p>No Profile Image</p>
                        <?php endif; ?>
                    </div>

                    <!-- Info -->
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

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/admin.js"></script>
</body>
</html>
