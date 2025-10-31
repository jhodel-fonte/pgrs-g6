<?php
require_once __DIR__ . '../../user/profile.php';
require_once __DIR__ . '../../user/account.php';

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
        $pass = $arrayInfo['pass'];

        $pgCode = $userProfile->addProfile($firstName, $lastName, $gender, $dob);
        if (isset($response['error'])) {
            throw new Exception('Profile creation failed');
        }
        $pgCode = $pgCode['pgID'];

        $regResult = $userAcc->register($username, $pass, $number, $pgCode, $email);
        if (!$regResult) {
            throw new Exception('Account registration failed');
        }

        return true;
    } catch (Exception $er) {
        error_log('CreateNewUserAccount error: ' . $er->getMessage());
        return ['error' => $er->getMessage()];
    }
}
?>
