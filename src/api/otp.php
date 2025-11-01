<?php
require_once __DIR__ . '/messageHandler.php';

function sendOtpToNumber($number) {
    $handler = new smsHandler();
    $res = $handler->sendOtp($number);
    return $res;
}

function verifyOtpForNumber($number, $otp) {
    $handler = new smsHandler();
    $res = $handler->verifyOtp($number, $otp);
    
    if ($res['staus'] == 'success') {
            return true;
        }
    return false;
}
?>


