<?php
require_once __DIR__ . '/messageHandler.php';

function sendOtpToNumber($number) {
    $handler = new smsHandler();
    $res = $handler->sendOtp($number);
    // echo $res;
    $response = json_decode($res, true);
    error_log(date('[Y-m-d H:i:s] ') . $res . PHP_EOL, 3, __DIR__ . '../../../log/account.log');
    if ($response['status'] == 'success') {
        $_SESSION['etcNUM'] = $number;

        return true;
    }
    return false;
}

function verifyOtpForNumber($number, $otp) {
    $handler = new smsHandler();
    $res = $handler->verifyOtp($number, $otp);
    $response = json_decode($res, true);
    // echo $res;

    if (!isset($response)) {
        $response = ['success' => 'error', 'message' => 'SMS Server Error'];
    }
    
    error_log(date('[Y-m-d H:i:s] ') . $res . PHP_EOL, 3, __DIR__ . '../../../log/account.log');
    
    if ($response['status'] == 'success' && $response['message'] == 'OTP verified successfully' ) {
            return true;
        }
    return false;
}