<?php 

require_once __DIR__ .'../../src/api/otp.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['otp'] === '1') {
    //send otp
    if (!isset($_SESSION['number'])) {
        header("Location: ../public/login.php");
        exit;
    }  



}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['resend'] === '1') {
    //resend otp
    if (!isset($_SESSION['number'])) {
        header("Location: ../public/login.php");
        exit;
    }  

    if (sendOtpToNumber($_SESSION['number'])) {
        header("Location: ../public/otp.php?res=1");
        exit;
    } else {
        header("Location: ../public/otp.php?res=2");
        exit;
    }


}


?>