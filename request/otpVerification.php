<?php

require_once __DIR__ . '/../src/api/otp.php';
require_once __DIR__ . '/../src/utillities/common.php';

header('Content-Type: application/json; charset=utf-8');

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
        echo json_encode(['success' => false, 'message' => 'OTP not provided']);
        exit;
    }

    $otp = sanitizeInput($_POST['otp']);

    if (empty($_SESSION['number'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Session number missing']);
        exit;
    }

    $number = $_SESSION['number'];

    if (verifyOtpForNumber($number, $otp)) {
        echo json_encode(['success' => true, 'message' => 'OTP verified']);
        exit;
    }

    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid OTP']);
    exit;

} catch (Exception $e) {
    error_log('OTP verification error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
    exit;
}

?>

