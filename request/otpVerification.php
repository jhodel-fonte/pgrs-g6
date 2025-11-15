<?php
require_once __DIR__ . '/../src/api/otp.php';
require_once __DIR__ . '/../src/utillities/common.php';
require_once __DIR__ . '/../src/utillities/log.php';

// unset($_SESSION['']);

// foreach ($_POST as $key => $value) {  /////comment this when debbugging
//     // echo "$key: $value<br>";
//     $array[] = $value;
// }

// var_dump($array);

ob_start();
header('Content-Type: application/json;');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    if (!isset($_POST['otp'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'OTP not providedzasa']);
        exit;
    }

    $otp = sanitizeInput($_POST['otp']);

    if (empty($_SESSION['number'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Session number missing']);
        exit;
    }

    $number = $_SESSION['number'];
    // $number = $_SESSION['number'];

    if (verifyOtpForNumber($number, $otp)) {
        $_SESSION['isValid'] = 1;
        $_SESSION['isOtpVerified'] = 1;
        $msg = json_encode(['success' => true, 'message' => 'OTP verified Successfully']);
        containlog('Log', $msg, __DIR__, 'verifyOtp.txt');
        echo $msg;
        
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Invalid OTPs']);
    exit;

} catch (Exception $e) {
    error_log('OTP verification error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
    exit;
}

?>

