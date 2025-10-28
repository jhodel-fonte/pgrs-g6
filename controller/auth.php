<?php

//mga bisaya dito lagi pupunta bago pag mag submit si public or user ng credential or data
// include_once "../src/user/user.php";

// $user = new User();

require __DIR__ .'../../src/auth/loginAuth.php';


if (isset($_GET['login']) && $_GET['login'] == 1) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $user = login($_POST['username'], $_POST['password']);

        if (isset($user['response'])) {
            header("Location: login.php?error=" . urlencode($user['response']));
            exit;
        } else {
   
            session_start();
            $_SESSION['user'] = $user;
            $_SESSION['isValid'] = 1; 

            header("Location: ../view/landing.php");
            exit;
        }
    }
}



// echo "es";
?>