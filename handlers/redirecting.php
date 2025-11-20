<?php 
// determines user authentication and redirects based on role
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and OTP is verified
if (empty($_SESSION) || !isset($_SESSION['user']) || !isset($_SESSION['isOtpVerified']) || $_SESSION['isOtpVerified'] !== 1) {
    header("Location: ../public/login.php");
    exit;
}

// Check if userprofile and roleId exist
if (!isset($_SESSION['user']['userprofile']) || !isset($_SESSION['user']['userprofile']['roleId'])) {
    header("Location: ../public/login.php?error=" . urlencode('Invalid session data. Please login again.'));
    exit;
}

$roleId = $_SESSION['user']['userprofile']['roleId'];

if ($roleId == 1) {
    header("Location: ../admin/dashboard.php");
    exit;
}

if ($roleId == 2) {
    // header("Location: ../admin/dashboard.php");
    exit;
}

if ($roleId == 3) {
    header("Location: ../public/user/user_dashboard.php");
    exit;
}

?>