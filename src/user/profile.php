<?php
//for modify sa data then pag kuha na din, bale madalas gamitin ito for profile page
require_once __DIR__ ."../../../data/Db.php";

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

    

    function updateUser(){//still not sure how to update by certain object

    }



}


?>