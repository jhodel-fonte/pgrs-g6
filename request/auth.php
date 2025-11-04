<?php

//mga bisaya dito lagi pupunta bago pag mag submit si public or user ng credential or data
// include_once "../src/user/user.php";

// $user = new User();

require_once __DIR__ . '../../src/auth/loginAuth.php';
require_once __DIR__ . '../../src/api/otp.php';

// echo ;

if (isset($_GET['login']) && $_GET['login'] == 1) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $user = login($_POST['username'], $_POST['password']);

        if (isset($user['response']) && $user['response'] == 'success') {
            session_start();
            // var_dump($user);
            $_SESSION['user'] = $user;
            $_SESSION['isValid'] = 1;
            $_SESSION['number'] = $user['userprofile']['mobileNum'];
            
            //send otp
           if (sendOtpToNumber($_SESSION['number'])) {
                    ob_clean();
                    $_SESSION['otp_sent_at'] = time();
                    header("Location: ../public/otp.php");
                }
                // current bug has no limit in sending so it possible to refresh then it resend new request
            
            exit;
        } else {
            header("Location: ../public/login.php?error=" . urlencode($user['message'] ?? 'Login failed'));
            exit;
        }
    }
}





// echo "es";
?>