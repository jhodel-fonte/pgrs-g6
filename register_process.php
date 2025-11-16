<?php
session_start();
require_once 'config/db.php'; // PDO connection
require_once 'config/smsHandler.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstname = trim($_POST['firstname']);
    $lastname  = trim($_POST['lastname']);
    $mobile    = trim($_POST['mobile']);
    $email     = trim($_POST['email']);
    $gender    = $_POST['gender'];
    $dob       = $_POST['dob'];
    $address   = trim($_POST['address']);
    $username  = trim($_POST['username']);
    $password  = $_POST['password'];
    $confirm   = $_POST['confirm_password'];

    // --- Password confirmation ---
    if ($password !== $confirm) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit();
    }

    // --- Check for duplicates ---
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ? OR mobile_number = ?");
    $stmt->execute([$username, $email, $mobile]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        $msg = "";
        if ($existing['username'] === $username) $msg .= "Username already exists.\n";
        if ($existing['email'] === $email) $msg .= "Email already exists.\n";
        if ($existing['mobile_number'] === $mobile) $msg .= "Mobile number already exists.\n";
        echo "<script>alert('{$msg}'); window.history.back();</script>";
        exit();
    }

    // --- Handle file uploads ---
    $profileDir = 'uploads/profile/';
    $idDir      = 'uploads/id/';

    if (!is_dir($profileDir)) mkdir($profileDir, 0755, true);
    if (!is_dir($idDir)) mkdir($idDir, 0755, true);

    $profilePic = null;
    $idDoc      = null;

    // Profile picture
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
        $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
        $profilePic = uniqid('profile_') . '.' . $ext;
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profileDir . $profilePic);
    } else {
        echo "<script>alert('Please upload a profile picture.'); window.history.back();</script>";
        exit();
    }

    // ID document
    if (isset($_FILES['id_doc']) && $_FILES['id_doc']['error'] === 0) {
        $ext = pathinfo($_FILES['id_doc']['name'], PATHINFO_EXTENSION);
        $idDoc = uniqid('id_') . '.' . $ext;
        move_uploaded_file($_FILES['id_doc']['tmp_name'], $idDir . $idDoc);
    } else {
        echo "<script>alert('Please upload a government ID.'); window.history.back();</script>";
        exit();
    }

    // --- Send OTP ---
    $sms = new smsHandler();
    $response = $sms->sendOtp($mobile);
    $res = json_decode($response, true);

    if (isset($res['status']) && $res['status'] === 'success') {
        // Save everything in session for OTP verification
        $_SESSION['pending_registration'] = [
            'firstname'    => $firstname,
            'lastname'     => $lastname,
            'mobile_number'=> $mobile,
            'email'        => $email,
            'gender'       => $gender,
            'dob'          => $dob,
            'address'      => $address,
            'username'     => $username,
            'password'     => password_hash($password, PASSWORD_DEFAULT), // store hashed
            'profile_pic'  => $profilePic,
            'id_doc'       => $idDoc
        ];

        header("Location: otp.php");
        exit();
    } else {
        echo "<script>alert('Failed to send OTP. Please check your number or try again later.'); window.history.back();</script>";
        exit();
    }
} else {
    header("Location: register.php");
    exit();
}
?>
