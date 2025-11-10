<?php
//this file to help some functions like input sanitation hashing
//tito yung helpers

function sanitizeInput($input) {//remove whitespace
    // $var = gettype($input);
    $noSlassh = stripslashes($input);    
    return htmlspecialchars(trim($noSlassh));
}

function securePassword($pass) {//returns a encrypted password
    $temp = sanitizeInput($pass);
    $saltedPassword = hash("sha256", $temp);  
    return $saltedPassword;
}

function verifyPassword($inputPass, $hashedPass) { //return bool result
    $temp = sanitizeInput($inputPass);
    return password_verify($temp, $hashedPass);
}

// $tempas = securePassword("test");
// echo $tempas;
// // $q = password_verify('test', $tempas);
// var_dump($q);

// ntlify free deployment app
//