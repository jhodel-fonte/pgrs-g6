<?php
//login logic and caller
//this one return a json that contains all the user details
//i mean this one handles the json that src returns when requested

//mga bisaya dito lagi pupunta bago pag mag submit si public or user ng credential or data
// include_once "../src/user/user.php";

// $user = new User();
session_start();

require_once __DIR__ . '/../src/auth/loginAuth.php';
require_once __DIR__ . '/../src/api/otp.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {

        $user = login($_POST['username'], $_POST['password']); // this one verify the username and password and return a array of the user details 
        
        // Check for login errors FIRST before accessing userprofile
        if (isset($user['response']) && $user['response'] == 'error') {
            ob_clean();
            header("Location: ../public/login.php?error=" . urlencode($user['message'] ?? 'Login failed'));
            exit;
        }

        // Verify that userprofile exists and has mobileNum
        if (!isset($user['userprofile']) || !isset($user['userprofile']['mobileNum'])) {
            ob_clean();
            header("Location: ../public/login.php?error=" . urlencode('Invalid user data. Please try again.'));
            exit;
        }

        // Set session data after successful login verification
        $_SESSION['user'] = $user;
        $_SESSION['number'] = $user['userprofile']['mobileNum'];
        $_SESSION['isOtpVerified'] = 0;

        // Check if OTP was already sent recently (within 60 seconds)
        if (isset($_SESSION['otp_sent_time']) && (time() < $_SESSION['otp_sent_time'])) {
            // OTP already sent, redirect to OTP page
            header("Location: ../public/otp.php");
            exit;
        }

        // Initialize OTP sending
        if (sendOtpToNumber($user['userprofile']['mobileNum'])) {
            header("Location: ../public/otp.php");
            exit;
        } else {
            // OTP sending failed
            ob_clean();
            unset($_SESSION['number']);
            unset($_SESSION['user']);
            header("Location: ../public/login.php?error=" . urlencode('Failed to send OTP. Please try again.'));
            exit;
        }

    } else {
        // Missing username or password
        ob_clean();
        header("Location: ../public/login.php?error=" . urlencode('Username and password are required.'));
        exit;
    }
} 

// Invalid request method or missing login parameter
header("Location: ../public/error.html");
exit;


?>