<?php

//mga bisaya dito lagi pupunta bago pag mag submit si public or user ng credential or data
// include_once "../src/user/user.php";

// $user = new User();

require_once __DIR__ .'../../src/auth/loginAuth.php';


if (isset($_GET['login']) && $_GET['login'] == 1) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $user = login($_POST['username'], $_POST['password']);

        if (isset($user['response']) && $user['response'] == 'success') {
            var_dump($user);
            
            // exit;
        } else {
   
            session_start();
            $_SESSION['user'] = $user;
            $_SESSION['isValid'] = 1; 
            echo '11';
            // header("Location: ../public/otp.php");
            exit;
        }
    }
}


/* require_once __DIR__ . '/../../src/auth/loginAuth.php';

if (isset($_GET['login']) && $_GET['login'] == 1) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $user = login($_POST['username'], $_POST['password']);

        if (isset($user['response']) && $user['response'] == 'success') {
            session_start();
            $_SESSION['user'] = $user;
            $_SESSION['isValid'] = 1;

            header("Location: ../public/otp.php");
            exit;
        } else {
            header("Location: ../public/login.php?error=" . urlencode($user['response'] ?? 'Login failed'));
            exit;
        }
    }
}
 */




// echo "es";
?>