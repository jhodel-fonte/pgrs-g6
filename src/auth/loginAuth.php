<?php 

//login verification 
require_once __DIR__ ."../../user/account.php";
require_once __DIR__ ."../../user/profile.php";
require_once __DIR__ ."../../utillities/common.php";

function login($uname, $password) {
    try {
        $tempAccount = new UserAcc();
        $result = $tempAccount->getAccByUsername($uname);
        // var_dump($result);
        if ($result != 0){
            $isUnameMatched = ($uname == $result['username']) ? true : false;
            $isPassMatched = verifyPassword($password, $result['saltedPass']);

            if ($isUnameMatched && $isPassMatched) {
                echo "Login Correct<br>";
                echo "<br>";
                //return array details
                $profile = new profileMng();
                $userProfile = $profile->getProfileDetailsByID($result['accId']);
                $response = [
                    'response' => 'success',
                    'userprofile' => $userProfile 
                ];
                return $response;

            } else {
                throw new Exception("Invalid Password");
            }
        } else {
            throw new Exception("Invalid Credentials");
        }

    } catch (Exception $r) {
        $response = [
            'response' => 'error',
            'message' => $r->getMessage()
        ];
        return $response;
    }

}


?>
