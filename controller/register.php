<?php

require_once '../src/utillities/common.php';

if (isset($_GET['register']) && $_GET['register'] == 1) {
    //reg
    if (isset($_POST['FirstName']) && isset($_POST['LastName']) && isset($_POST['number']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {
        if ($_POST['password'] == $_POST['confirm_password']) {
            $userReg = [
                "firstName" => sanitizeInput($_POST['FirstName']),
                "lastName" => sanitizeInput($_POST['LastName']),
                "number" => sanitizeInput($_POST['number']),
                "email" => sanitizeInput($_POST['password']),
                "gender" => sanitizeInput($_POST['gender']),
                "username" => sanitizeInput($_POST['username']),
                "pass" => sanitizeInput($_POST['password'])
            ];
        }
        
        
    }
}


/* 
$_POST['FirstName']
$_POST['LastName']
$_POST['number']
$_POST['email']
$_POST['password']
$_POST['confirm_password']
 */

?>