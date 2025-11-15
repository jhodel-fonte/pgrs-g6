<?php
//this file to help some functions like input sanitation hashing
//tito yung helpers
require_once __DIR__ .'../../user/profile.php';

function sanitizeInput($input) {//remove whitespace
    // $var = gettype($input);
    $noSlassh = stripslashes($input);    
    return htmlspecialchars(trim($noSlassh));
}

function securePassword($pass) {//returns a encrypted password
    $temp = sanitizeInput($pass);
    $saltedPassword = password_hash($temp, PASSWORD_DEFAULT);
    return $saltedPassword;
}

function verifyPassword($inputPass, $hashedPass) { //return bool result
    $temp = sanitizeInput($inputPass);
    return password_verify($temp, $hashedPass);
}


function updateSession($id) {
    $temp = new profileMng;
    $user = $temp->getProfileDetailsByID($id);
    $_SESSION['user'] = [
                    'response' => 'success',
                    'userprofile' => $user
                ];
}

// $tempas = securePassword("test");
// echo $tempas;
// // $q = password_verify('test', $tempas);
// var_dump($q);

// ntlify free deployment app
//