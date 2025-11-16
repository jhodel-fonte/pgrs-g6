<?php
session_start();
require_once 'config/db.php';
require_once 'config/smsHandler.php';

$sms = new smsHandler();

if (!isset($_SESSION['pending_registration'])) {
    echo "<script>alert('No registration data found. Please register first.');window.location='register.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Combine OTP digits into one string
    $otp_input = '';
    if (isset($_POST['otp'])) {
        if (is_array($_POST['otp'])) {
            $otp_input = trim(implode('', $_POST['otp']));
        } else {
            $otp_input = trim($_POST['otp']);
        }
    }

    // Ensure OTP isn’t empty
    if (empty($otp_input)) {
        echo "<script>alert('Please enter your OTP.');window.location='otp.php';</script>";
        exit();
    }

    $user = $_SESSION['pending_registration'];

    // Verify OTP via SMS provider
    $api_response = $sms->verifyOtp($user['mobile_number'], $otp_input);
    $response = json_decode($api_response, true);

    if ($response === null) {
        echo "<script>alert('Invalid response from OTP server. Please try again.');window.location='otp.php';</script>";
        exit();
    }

    // ✅ OTP verified
    if (isset($response['status']) && strtolower($response['status']) === 'success') {
        try {
            $stmt = $pdo->prepare("INSERT INTO users 
                (firstname, lastname, mobile_number, email, gender, dob, address, username, password, status, profile_pic, id_doc)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', ?, ?)");
            $stmt->execute([
                $user['firstname'],
                $user['lastname'],
                $user['mobile_number'], // ✅ fixed
                $user['email'],
                $user['gender'],
                $user['dob'],
                $user['address'],
                $user['username'],
                $user['password'], // already hashed
                $user['profile_pic'],
                $user['id_doc']
            ]);

            unset($_SESSION['pending_registration']);
            echo "<script>alert('OTP verified! Your account is pending admin approval.');window.location='login.php';</script>";
            exit();

        } catch (PDOException $e) {
            echo "<script>alert('Database error: " . addslashes($e->getMessage()) . "');window.location='register.php';</script>";
            exit();
        }

    } else {
        $msg = isset($response['message']) ? $response['message'] : 'Invalid OTP. Please try again.';
        echo "<script>alert('{$msg}');window.location='otp.php';</script>";
        exit();
    }
} else {
    header("Location: otp.php");
    exit();
}
?>
