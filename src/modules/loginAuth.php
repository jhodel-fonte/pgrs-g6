<?php 

//login verification 
require_once __DIR__ . '/../data/account.php';
require_once __DIR__ . '/../data/profile.php';
require_once __DIR__ . '/../utillities/common.php';

function login($uname, $password) { //checks if username and pass have same in db
    try {
        $tempAccount = new UserAcc(); //init acc

        $result = $tempAccount->getAccByUsername($uname);//get acc same username in db is available then return a array or false
        // var_dump($result);

        if ($result != 0){// check the return contents

            $isUnameMatched = ($uname == $result['username']) ? true : false;
            $isPassMatched = verifyPassword($password, $result['saltedPass']);

            if ($isUnameMatched && $isPassMatched) {
                
                error_log("Login successful for user: " . $uname); // Log successful login

                //return array details
                $profile = new profileMng();
                $userProfile = $profile->getProfileDetailsByID($result['accId']);
                $response = [
                    'response' => 'success',
                    'userprofile' => $userProfile 
                ];
                
                // var_dump($userProfile);
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
