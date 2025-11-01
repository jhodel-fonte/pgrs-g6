<?php
//for modify sa data then pag kuha na din, bale madalas gamitin ito for profile page
require_once __DIR__ ."../../data/Db.php";
require_once __DIR__ ."../../utillities/common.php";

class profileMng {  //Profile functions for user
    private $User;
    private $conn;

    function __construct() {//use user object or i think better the Id, hmm lets seee
        // $this->User = $userObj;
        $dbObj = new Database();
        $this->conn = $dbObj->getConn();
    }
    
    function getProfile($id) {
        try {
            $query = $this->conn->prepare("Call GetProfileById(?)");
            $id = sanitizeInput($id);
            $query->bind_param("i", $id);
            $query->execute();
            $result = $query->get_result();

            if ($result && $result->num_rows > 0) {
                return $result->fetch_assoc();
            } else {
                throw new Exception("No Profile results found for ID: $id");
            }

        } catch (Exception $r) {
            echo "Message: " .$r->getMessage();
            return 0;
        }
    }

        function getProfileDetailsByID($id) {
        try {
            $query = $this->conn->prepare("Call GetConfirnedUserAcc(?)");
            $id = sanitizeInput($id);
            $query->bind_param("i", $id);
            $query->execute();
            $result = $query->get_result();

            if ($result && $result->num_rows > 0) {
                return $result->fetch_assoc();
            } else {
                throw new Exception("No Results found for ID: $id");
            }

        } catch (Exception $r) {
            echo "Message: " .$r->getMessage();
            return 0;
        }
    }

    function addProfile($fName, $lName, $Gnder, $DOB) {
        try {
            $fName = sanitizeInput($fName);
            $lName = sanitizeInput($lName);
            $Gnder = sanitizeInput($Gnder);
            $DOB = sanitizeInput($DOB);

            $reg = $this->conn->prepare("Call CreateNewProfile(?, ?, ?, ?)");
            $reg->bind_param("ssss", $fName, $lName, $Gnder, $DOB);

            if (!$reg->execute()){
                throw new Exception($reg->error);
            };

            $result = $reg->get_result();
            if ($result && $row = $result->fetch_assoc()) {
                $newId = $row['pgId'];
                if ($newId == null){
                    throw new Exception('No changes found');
                }
                return ["pgID" => $newId];
            }

            // return true;

        } catch (Exception $errs) {
            echo "<script>console.log('Account Update Error! Check Log For details')</script>";
            error_log(date('[Y-m-d H:i:s] ') . $errs->getMessage() . PHP_EOL, 3, __DIR__ . '../../../log/account.log');
            return ["error" => "Error"];
        }
    }

    

    function updateUser(){//still not sure how to update by certain object

    }



}
// $userProfile = new profileMng();
// $userProfile->addProfile('mm', 'sr', 'male', '06/10/2025');
?>