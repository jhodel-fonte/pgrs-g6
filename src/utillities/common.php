<?php
//this file to help some functions like input sanitation hashing
//this is a utility function

function sanitizeInput($input) {//sanitize and remove whitespace
    return htmlspecialchars(trim($input));
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

?>