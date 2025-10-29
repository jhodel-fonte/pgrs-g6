<?php

require_once __DIR__ .'../../user/profile.php';
require_once __DIR__ .'../../user/account.php';


function CreateNewUserAccount($arrayInfo) {

    try {
        $userProfile = new profileMng();
        $userAcc = new UserAcc();

        $firstName = sanitizeInput($arrayInfo['firstName']);
        $lastName = sanitizeInput($arrayInfo['lastName']);
        $gender = sanitizeInput($arrayInfo['gender']);
        $dob = sanitizeInput($arrayInfo['dob']);

        $number = sanitizeInput($arrayInfo['number']);
        $email = sanitizeInput($arrayInfo['email']);
        
        if ($userProfile->addProfile($firstName, $lastName, $gender, $dob)) {
            $response = ["profile" => 'Registered Sucess'];
        } else {
            throw new Exception('Error Register');
        }

        $userAcc->register()

        if () {}

    } catch (Exception $er) {
        return ['error' => $er->getMessage()];
    }
    
}


?>