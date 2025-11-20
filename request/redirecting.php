<?php 
// This file determines user authentication and redirects based on role
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// foreach ($_SESSION as $value => $data) {
//     echo $value .": " .$data ."<br>";

// }

// var_dump($_SESSION['user']['userprofile']);

// exit;

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

// Route based on roleId
// roleId 1 = Admin
// roleId 2 or 3 = Response Team (admin2)
// roleId 4 = Regular User
switch ($roleId) {
    case 1:
        // Admin
        header("Location: ../admin/dashboard.php");
        exit;
        break;
        
    case 2:
    case 3:
        // Response Team
        header("Location: ../public/admin2/admin_dashboard.php");
        exit;
        break;
        
    case 4:
    default:
        // Regular User
        header("Location: ../public/user/user_dashboard.php");
        exit;
        break;
}
?>