<?php
//dito class ng mga bisaya
//i mean dito ilalagay ang data na nakuha galing sa db
//dito ilalagay ang data tas dito
// include "../../data/Db.php";

//this is for account this class mostly use when verifying user
//Account Table

require_once __DIR__ ."../../data/Db.php";
require_once __DIR__ ."../../utillities/common.php";
require_once __DIR__ ."../../utillities/dbUpdate.php";

class UserAcc {//this class communicates to database Account Table
    private $conn;
    private $userList;
    private $accId;
    private $name;  // still not sure if i use this variables
    private $username;
    private $passHash;//always salt the password the time it enter the server
    private $auth; //not sure for this variable but mostly it is for role i guess

    //get database
    function __construct(){
        $dbObj = new Database();
        $this->conn = $dbObj->getConn();
    }

    //arrau returns an array all results based on Id
    function getAccById($id) {
        try {
            $query = $this->conn->prepare("Call SelectUserAccById(?)");
            $id = sanitizeInput($id);
            $query->bind_param("i", $id);
            $query->execute();
            $result = $query->get_result();

            if ($result && $result->num_rows > 0) {
                return $result->fetch_assoc();
            } else {
                throw new Exception("No Account results found for ID: $id");
            }

        } catch (Exception $r) {
            echo "Message: " .$r->getMessage();
            exit();
        }
    }

    function isUsernameRegistered($uname) {
        $uname = sanitizeInput($uname);
        $existing = $this->getAccByUsername($uname);

        if ($existing === 0) {
            return false;
        }

        return !empty($existing);
    }


    //create account 
    function register($username, $hashedPass, $mNumber, $pgCode, $email) {
        
        try {
            $num = sanitizeInput($mNumber);

            if ($this->isUsernameRegistered($username)) {
                // echo '11';
                throw new Exception('Already Have Username');
            }

            $defaultRole = 4;
            $defaultStatus = 4;

            $reg = $this->conn->prepare("Call CreateNewAccount(?, ?, ?, ?, ?, ?, ?)");
            // var_dump($num);
            $reg->bind_param("sssiiis", $username, $hashedPass, $num, $defaultRole, $defaultStatus, $pgCode, $email);
            
            if (!$reg->execute()){
                throw new Exception($reg->error);
            };
            // echo $reg->insert_id;
            return true;

        } catch (Exception $errs) {
            echo "<script>console.log('Account Update Error! Check Log For details')</script>";
            error_log(date('[Y-m-d H:i:s] ') . $errs->getMessage() . PHP_EOL, 3, __DIR__ . '../../../log/account.log');
            return ['status' => 'error'];
            exit();
        }
    }

    //update account
    function update($id, $key, $value){
        // $keyList = [1 => "username", 2 => "saltedPass", 3 => "mobileNum", 4 => "roleId", 5 => "statusId"];

        $keyList = getTableRows($this->conn, 'account');
        $id = sanitizeInput($id);
        $value = sanitizeInput($value);

        try {
            if (!in_array($key, $keyList, true)) {
                // echo "Updated : " .$key ." = " .$value;
                throw new Exception("Exception : Fail to Update Data! Unauthorize Account Table Row Key : " .$key);
            }

            /*if (array_key_exists($key, $keyList)) {
                $key = $keyList[$key];
            } else {
                throw new Exception("Exception : Fail to Update Data! Unauthorize Account Table Row Key : " .$key);
            } */

            $update = $this->conn->prepare("CALL `UpdateAccById`(?, ?, ?)");
            $update->bind_param("iss", $id, $key, $value);
            
            if (!$update->execute()){
                throw new Exception($update->error);
            };
            // echo "Updated : " .$key ." = " .$value;
            return $update->affected_rows;//returms if has effect on database rows

        } catch (Exception $p) {
            echo "<script>console.log('Account Update Error! Check Log For details')</script>";
            error_log(date('[Y-m-d H:i:s] ') . $p->getMessage() . PHP_EOL, 3, __DIR__ . '../../../log/account.log');
            exit();
        }
    }

    //just removing user from databas
    function remove($id, $isValid) {

    }

    function getAccByUsername($userN) {
        try {
            $query = $this->conn->prepare("Call SelectUserAccByUname(?)");
            // echo $userN;
            $id = sanitizeInput($userN);
            $query->bind_param("s", $id);
            $query->execute();
            $result = $query->get_result();

            if ($result && $result->num_rows > 0) {
                return $result->fetch_assoc();
            } else {
                throw new Exception("No Account results found for ID: $id");
            }

        } catch (Exception $r) {
            // echo "Message: " .$r->getMessage();
            return 0;
        }
    }

}

// $temp = new UserAcc();
// $data = $temp->getAccById(1);
// echo $data;
// var_dump($data);

// foreach ($data as $user) {
//     echo $user;
// }

// $mpp = securePassword('ehh');
// $temp->update(1, "roleId", 2);
// if ($temp->register("abcde", $mpp, "091234567", 100, "fontejoel@gmail.com")){
//     echo "Register Success";
// }

?>