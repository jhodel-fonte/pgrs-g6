<?php
//for modify sa data then pag kuha na din, bale madalas gamitin ito for profile page
require_once __DIR__ ."../../data/Db.php";
require_once __DIR__ ."../../utillities/common.php";

class profileMng {  //Profile functions for user
    private $conn;

    function __construct() {//use user object or i think better the Id, hmm lets seee
        $dbObj = new Database();
        $this->conn = $dbObj->getConn();
    }
    
    function getConn() {
        return $this->conn;
    }

    function getUserByRole($role) {
        try {
            $role = sanitizeInput($role);
            $query = $this->conn->prepare("SELECT * FROM `profile` WHERE `role_id` = ?");
            $query->execute([$role]);
            $result = $query->fetchAll();
            // var_dump($result);
            
            if ($result) {
                return $result;
            } else {
                throw new Exception("No Profile results found");
            }

        } catch (Exception $r) {
            return ['success' => false,'message' => $r->getMessage()];
        }
    }

    function getProfile($id) {
        try {
            // $query = $this->conn->prepare("Call GetProfileById(?)");
            $query = $this->conn->prepare("SELECT * FROM `profile` WHERE `userId` = ? LIMIT 1");
            $id = sanitizeInput($id);
            $query->execute([$id]);
            $result = $query->fetch();

            if ($result) {
                return $result;
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
            $query = $this->conn->prepare("SELECT 
                                                a.accId,
                                                p.userId as pgCode,
                                                a.username,
                                                a.email,
                                                a.mobileNum,
                                                a.roleId,
                                                a.statusId,
                                                p.firstName,
                                                p.lastName,
                                                p.gender,
                                                p.dateOfBirth
                                            FROM 
                                                account AS a
                                            INNER JOIN 
                                                profile AS p ON a.pgCode = p.userId
                                            WHERE a.accId = ?
                                            LIMIT 1");
            $id = sanitizeInput($id);
            $query->execute([$id]);
            $result = $query->fetch();

            if ($result) {
                return $result;
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

            // $reg = $this->conn->prepare("Call CreateNewProfile(?, ?, ?, ?)");
            $reg = $this->conn->prepare("INSERT INTO `profile`(`firstName`, `lastName`, `gender`, `dateOfBirth`) 
                                            VALUES (?, ?, ?, ?);");

            if (!$reg->execute([$fName, $lName, $Gnder, $DOB])){
                throw new Exception("Failed to execute query");
            };

            $newId = $this->conn->lastInsertId();

            if ($newId === 0 || $newId === null) {
                throw new Exception('No new ID was generated, insertion may have failed.');
            }

            return ["pgID" => $newId];

        } catch (Exception $errs) {
            echo "<script>console.log('Account Update Error! Check Log For details')</script>";
            error_log(date('[Y-m-d H:i:s] ') . $errs->getMessage() . PHP_EOL, 3, __DIR__ . '../../../log/account.log');
            return ["error" => "Error"];
        }
    }

    function deleteUser($id) {
        $id = sanitizeInput($id);
    
        try {
            $stmt_profile = $this->conn->prepare("DELETE FROM profile WHERE `userId` = ?");

            if (!$stmt_profile->execute([$id])) {
                throw new Exception("Error deleting profile");
            }
            return true;

        } catch (Exception $e) {
            throw new Exception("Failed to delete user with ID $id: " . $e->getMessage());
            return false;
        }
    }

    

    function updateUser(){//still not sure how to update by certain object

    }



}
// $userProfile = new profileMng();
// $userProfile->addProfile('mm', 'sr', 'male', '06/10/2025');
?>