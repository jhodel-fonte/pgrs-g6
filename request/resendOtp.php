<?php 

require_once __DIR__ .'../../src/api/otp.php';

session_start();

/* if (!empty($_SESSION)) {
    foreach ($_SESSION as $key => $value) {
        echo "<p>Variable: " . htmlspecialchars($key) . "</p>";

        if (is_array($value)) {
            echo "<p>Value: (Array)</p>\n";
            echo "<pre>" . htmlspecialchars(print_r($value, true)) . "</pre>\n";
        } else {
            echo "<p>Value: " . htmlspecialchars($value) . "</p>\n";
        }
    }
} else {
    echo "<p>No session variables are currently set.</p>\n";
} */

//this one just call get the otp stored in the session then resend it

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