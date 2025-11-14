<?php
//login logic and caller
//this one return a json that contains all the user details
//i mean this one handles the json that src returns when requested

//mga bisaya dito lagi pupunta bago pag mag submit si public or user ng credential or data
// include_once "../src/user/user.php";

// $user = new User();
session_start();

require_once __DIR__ . '../../src/auth/loginAuth.php';
require_once __DIR__ . '../../src/api/otp.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['login']) && $_GET['login'] == 1) {
    echo "Getting Login";
    if (isset($_POST['username']) && isset($_POST['password'])) {

        $user = login($_POST['username'], $_POST['password']); // this one verify the username and password and return a array of the user details 
        
        //set the data
        $_SESSION['number'] = $user['userprofile']['mobileNum'];

        // $_SESSION['otp_sent'] = 0;
        $_SESSION['isOtpVerified'] = 0;

        if (isset($user['response']) && $user['response'] == 'error') {//if error in username and password
            ob_clean();
            header("Location: ../public/login.php?error=" . urlencode($user['message'] ?? 'Login failed'));
            exit;
        }

        //if not has already otp sent for this session
        if (isset($_SESSION['otp_sent']) && (time() - $_SESSION['otp_sent_at']) <= 60 && !isset($_SESSION['user'])){
            echo "Has Otp Sent";
            header("Location: ../public/otp.php");
            exit;
        }

        //initialize otp sending
        if (sendOtpToNumber($user['userprofile']['mobileNum'])) {
            $_SESSION['user'] = $user;
            header("Location: ../public/otp.php");
            exit;
        }

    } 

    echo json_encode(['response' => "error", 'message' => 'No Set Username or Password']);
    exit;

} 

header("Location: ../public/error.html");


?>