<?php

echo json_encode(['status' => 'error', 'message' => 'Passwords do not match!']);

// ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ .'../../src/utillities/common.php';
require_once __DIR__ .'../../src/auth/registerAuth.php';
require_once __DIR__ .'../../src/api/otp.php';

foreach ($_POST as $key => $value) {  
    // echo "$key: $value<br>"; /////comment this when debbugging
    $requiredFields[] = $key;
}

// echo json_encode(['message' => 'Form submitted!', 'status' => 'success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // echo 'Geeting Post Request';
    if (isset($_GET['register']) && $_GET['register'] == 1) {
        try {
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("All fields are required.");
                }
            }

            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email.");
            }

        /*  if (strlen($_POST['password']) < 8) {
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
                "address" => sanitizeInput($_POST['address']),
                "username" => sanitizeInput($_POST['username']),
                "pass" => securePassword($_POST['password']),
                "dob" => sanitizeInput($_POST['dob'])
            ];

            $set = CreateNewUserAccount($userReg);
            $_SESSION['number'] = $userReg['number'];

            if ($set['response'] == 'success'){
                if (!sendOtpToNumber($userReg['number'])) {
                    throw new Exception('Server Error');
                }
                // echo "<script>alert('Registered Success! OTP Verification Sent');</script>";
                header('Location: ../public/otp.php?reg=1');
            }   else {
                throw new Exception($set['message']);
            }
  
        } catch (Exception $e) {
            // echo "<script>alert('Error: " . htmlspecialchars($e->getMessage()) . "');</script>";
            echo "<script>alert('Error: " . htmlspecialchars($e->getMessage()) . "'); window.location.href = '../public/register.php';</script>";
            // ob_clean(); //this to only send ung tamang response

            // header('Content-Type: application/json');
            // echo json_encode(['message' => $e->getMessage(), 'status' => 'error']);

        }

    }



    
}


?>
