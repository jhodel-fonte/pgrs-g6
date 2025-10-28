<?php
//this file to help some functions like input sanitation hashing
//this is a utility function

function sanitizeInput($input) {//remove whitespace
    $var = gettype($input);
    return htmlspecialchars(trim($input));
/* 
    switch ($var) {

        case 'string':
            return htmlspecialchars(trim($input));
        case 'int':
            return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
        default:
            return false;
    }
     */
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

// echo sanitizeInput('sas');

?>