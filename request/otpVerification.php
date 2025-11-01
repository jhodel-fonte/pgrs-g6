<?php

require_once __DIR__ .'../../src/api/otp.php';
require_once __DIR__ .'../../src/utillities/common.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {
        if (isset($_SERVER['otp'])) {
        $otp = sanitizeInput($_SERVER['otp']);
        
        if (verifyOtpForNumber($_SESSION['number'], $otp)) {
            echo "Otp verify Success";
            header("Location: ../../public/landing.php");
        } 

        echo "OTP Validation Error";

    }

    } catch (Exception $r)  {

    } 
    
}


?>

