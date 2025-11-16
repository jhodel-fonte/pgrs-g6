<?php
session_start();
require '../config/db.php';
require '../config/smsHandler.php';

// 🔒 Admin Access Check
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ✅ SMS Sender Function
function sendSMS($mobile, $message) {
    $sms = new smsHandler();
    $response = $sms->sendSms([
        'phoneNumber' => $mobile,
        'message' => $message
    ]);
    $res = json_decode($response, true);
    return isset($res['status']) && $res['status'] === 'success';
}

$message = '';
$alertClass = '';

// ✅ Handle Team Account Creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname']);
    $lastname  = trim($_POST['lastname']);
    $username  = trim($_POST['username']);
    $mobile    = trim($_POST['mobile_number']);
    $email     = trim($_POST['email']);
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // 🔥 Correct role for your database
    $role = "response_team";

    // 🔧 Default values for required columns not in the form
    $gender  = "Not Specified";
    $dob     = "2000-01-01";
    $address = "Response Team Office";

    // Check duplicate username or email
    $check = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $check->execute([$username, $email]);

    if ($check->rowCount() > 0) {
        $message = "❌ Username or Email already exists!";
        $alertClass = "alert-danger";
    } else {

        $stmt = $pdo->prepare("
            INSERT INTO users 
            (firstname, lastname, username, mobile_number, email, password, gender, dob, address, role, status, is_approved)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Approved', 1)
        ");

        $stmt->execute([
            $firstname,
            $lastname,
            $username,
            $mobile,
            $email,
            $password,
            $gender,
            $dob,
            $address,
            $role
        ]);

        // Send SMS notification
        sendSMS($mobile, 
            "Welcome {$firstname}! Your Response Team account for Padre Garcia Service Report System has been successfully created. You can now log in and assist in community emergencies."
        );

        header("Location: manage_users.php?created=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Response Team | Padre Garcia Reporting</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/admin.css" rel="stylesheet">
</head>
<body>
<div class="admin-bg">
    <?php include '../admin/admin_sidebar.php'; ?>

    <div class="main-content">
      <div class="container mt-4">
        <div class="card-custom p-4 shadow-lg border border-secondary rounded-4 bg-dark text-light">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="text-neon mb-0">Create Response Team Account</h3>
                <a href="manage_users.php" class="btn btn-outline-light">⬅ Back</a>
            </div>

            <?php if ($message): ?>
                <div class="alert <?= $alertClass; ?>"><?= $message; ?></div>
            <?php endif; ?>

            <form method="POST" novalidate>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>First Name</label>
                        <input type="text" name="firstname" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Last Name</label>
                        <input type="text" name="lastname" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Mobile Number</label>
                        <input type="text" name="mobile_number" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-3 py-2">
                    Create Response Team Account
                </button>
            </form>
        </div>
     </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
