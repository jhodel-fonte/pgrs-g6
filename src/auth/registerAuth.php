<?php
require_once __DIR__ . '/../user/profile.php';
require_once __DIR__ . '/../user/account.php';

function CreateNewUserAccount($arrayInfo) {
    try {
        $userProfile = new profileMng();
        $userAcc = new UserAcc();

        $firstName = sanitizeInput($arrayInfo['firstName']);
        $lastName = sanitizeInput($arrayInfo['lastName']);
        $gender = isset($arrayInfo['gender']) ? sanitizeInput($arrayInfo['gender']) : null;
        $dob = isset($arrayInfo['dob']) ? sanitizeInput($arrayInfo['dob']) : null;
        $number = sanitizeInput($arrayInfo['number']);
        $email = sanitizeInput($arrayInfo['email']);
        $username = sanitizeInput($arrayInfo['username']);
        $pass = sanitizeInput($arrayInfo['pass']);

        // Check if username is already registered
        // if ($userAcc->isUsernameRegistered($username)) {
        //     throw new Exception('Username already exists');
        // }

        if ($userAcc->isUsernameRegistered($username)) {
            // echo 'Yes';
            throw new Exception('Already Have Username');
        }

        $profileResult = $userProfile->addProfile($firstName, $lastName, $gender, $dob);
        if (isset($profileResult['error'])) {
            throw new Exception('Profile creation failed: ' . $profileResult['error']);
        }
        $pgCode = $profileResult['pgID']; 

        $regResult = $userAcc->register($username, $pass, $number, $pgCode, $email);
        if (!$regResult) {
            throw new Exception('Account registration failed');
        }

        return [
            'response' => 'success',    
        ];
    } catch (Exception $er) {
        error_log('CreateNewUserAccount error: ' . $er->getMessage());
        return ['message' => $er->getMessage(),
        'response' => 'error'];
    }
}
