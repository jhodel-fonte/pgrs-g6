<?php
session_start();
// ----------------------------
// DEMO ADMIN DATA (Replace with DB later)
// ----------------------------
$admin = [
    "id"            => 1,
    "firstname"     => "Admin",
    "lastname"      => "User",
    "mobile_number" => "09171234567",
    "username"      => "admin123",
    "email"         => "admin@example.com",
    "gender"        => "Male",
    "dob"           => "1990-06-15",
    "address"       => "Padre Garcia, Batangas",
    "role"          => "admin",
    "is_approved"   => 1,
    "created_at"    => "2023-01-10 14:22:00",
    "status"        => "Active",
    "profile_pic"   => "profile_6909db8442615.jpg",
    "id_doc"        => "sample_id.jpg"
];

// SIMULATED UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['success'] = "Profile updated successfully! Unexpected error occur!";
    header("Location: my_profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Profile | Admin</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="../admin/assets/admin.css">

</head>

<body>

<?php include '../admin/admin_sidebar.php'; ?>

<div class="main-content">

    <div class="card-customs scroll-cards p-4">

        <h3 class="mb-3">My Profile</h3>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <div class="row">

                <!-- LEFT SIDE: Profile Picture -->
                <div class="col-md-4 text-center border-end">
                    <img src="../assets/uploads/profile/<?= $admin['profile_pic']; ?>" 
                         class="img-fluid rounded-circle mb-3" 
                         style="width: 160px; height:160px; object-fit:cover;">
                    <input type="file" name="profile_pic" class="form-control">
                </div>

                <!-- RIGHT SIDE: Details -->
                <div class="col-md-8">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>First Name</label>
                            <input type="text" name="firstname" class="form-control" 
                                   value="<?= $admin['firstname']; ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Last Name</label>
                            <input type="text" name="lastname" class="form-control" 
                                   value="<?= $admin['lastname']; ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Mobile Number</label>
                            <input type="text" name="mobile_number" class="form-control" 
                                   value="<?= $admin['mobile_number']; ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" 
                                   value="<?= $admin['username']; ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?= $admin['email']; ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Gender</label>
                            <select name="gender" class="form-control">
                                <option <?= $admin['gender']=="Male"?"selected":"" ?>>Male</option>
                                <option <?= $admin['gender']=="Female"?"selected":"" ?>>Female</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Date of Birth</label>
                            <input type="date" name="dob" class="form-control" 
                                   value="<?= $admin['dob']; ?>">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Address</label>
                            <textarea class="form-control" name="address"><?= $admin['address']; ?></textarea>
                        </div>

                        <!-- NON-EDITABLE SYSTEM FIELDS -->
                        <div class="col-md-6 mb-3">
                            <label>Role</label>
                            <input type="text" class="form-control" value="<?= $admin['role']; ?>" disabled>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Status</label>
                            <input type="text" class="form-control" value="<?= $admin['status']; ?>" disabled>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Created At</label>
                            <input type="text" class="form-control" value="<?= $admin['created_at']; ?>" disabled>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Approval</label>
                            <input type="text" class="form-control" 
                                   value="<?= $admin['is_approved'] ? 'Approved' : 'Pending'; ?>" disabled>
                        </div>

                    </div>
                </div>
            </div>

            <div class="mt-4 text-end">
                <button class="btn btn-primary px-4">Save Changes</button>
            </div>

        </form>
    </div>
</div>

<script src="../admin/assets/admin.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
