<?php
require_once __DIR__ .'../../src/utillities/common.php';
require_once __DIR__ .'../../src/auth/registerAuth.php';

if (isset($_GET['register']) && $_GET['register'] == 1) {
    try {
        $requiredFields = ['FirstName', 'LastName', 'number', 'email', 'password', 'confirm_password', 'gender', 'username'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("All fields are required.");
            }
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email.");
        }
/*         if (strlen($_POST['password']) < 8) {
            throw new Exception("Password must be at least 8 characters.");
        } */
        if ($_POST['password'] !== $_POST['confirm_password']) {
            throw new Exception("Passwords do not match.");
        }

        $userReg = [
            "firstName" => sanitizeInput($_POST['FirstName']),
            "lastName" => sanitizeInput($_POST['LastName']),
            "number" => sanitizeInput($_POST['number']),
            "email" => sanitizeInput($_POST['email']),
            "gender" => sanitizeInput($_POST['gender']),
            "username" => sanitizeInput($_POST['username']),
            "pass" =>securePassword($_POST['password'])
        ];

        $res = CreateNewUserAccount($userReg);

        var_dump($res);

        if (isset($res['error'])){
            throw new Exception("Registration failed. Please try again.");
        }   

        echo "<script>alert('Registration Successful!'); window.location.href = '../view/login.php';</script>";

    } catch (Exception $e) {
        echo "<script>alert('Error: " . htmlspecialchars($e->getMessage()) . "');</script>";
        echo "<script>
        0.window.location.href = '../view/login.php';</script>";

    }

}
?>
