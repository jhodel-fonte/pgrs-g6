<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ .'/config/API/smsHandler.php';
require_once './config/Database/dbqueries.php';

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
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match!']);
        exit();
    }

    // --- Check for duplicates ---
    $db = new DbQueries();
    $existing = $db->checkDuplicateUser($username, $email, $mobile);
    // var_dump($existing);

    if ($existing) {
        $msg = "";
        if ($existing['username'] === $username) $msg .= "Username already exists.";
        if ($existing['email'] === $email) $msg .= "Email already exists.\n";
        if (isset($existing['mobile_number']) && $existing['mobile_number'] === $mobile) $msg .= "Mobile number already exists.";
        echo json_encode(['status' => 'error', 'message' => $msg]);
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
        echo json_encode(['status' => 'error', 'message' => 'Please upload a profile picture.']);
        exit();
    }

    // ID document
    if (isset($_FILES['id_doc']) && $_FILES['id_doc']['error'] === 0) {
        $ext = pathinfo($_FILES['id_doc']['name'], PATHINFO_EXTENSION);
        $idDoc = uniqid('id_') . '.' . $ext;
        move_uploaded_file($_FILES['id_doc']['tmp_name'], $idDir . $idDoc);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Please upload a Valid ID.']);
        exit();
    }

    // --- Send OTP ---
    $sms = new smsHandler();
    $response = $sms->sendOtp($mobile);
    $res = json_decode($response, true);

    if (isset($res['status']) && $res['status'] === 'success') {
        // Save mobile and pending registration in session for OTP verification
        $_SESSION['mobile_number'] = $mobile;
        $_SESSION['pending_registration'] = [
            'firstname'    => $firstname,
            'lastname'     => $lastname,
            'mobile_number'=> $mobile,
            'email'        => $email,
            'gender'       => $gender,
            'dob'          => $dob,
            'address'      => $address,
            'username'     => $username,
            'password'     => password_hash($password, PASSWORD_DEFAULT),
            'profile_pic'  => $profilePic,
            'id_doc'       => $idDoc
        ];

        // Return JSON only — client will redirect
        echo json_encode(['status' => 'success', 'message' => 'Registration successful! Redirecting to OTP verification...']);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP. Please check your number or try again later.']);
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit();
}
?>
