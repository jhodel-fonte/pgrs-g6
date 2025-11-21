<?php
require_once 'Db.php';

class Teams {
    private $conn;

    function __construct() {
        $dbObj = new Database();
        $this->conn = $dbObj->getConn();
    }
    
    function getConn() {
        return $this->conn;
    }

    function getAllTeams() {
        try {
            $query = $this->conn->prepare("SELECT * FROM `response_team` WHERE 1");
            
            if (!$query->execute()) {
                throw new Exception("An Error Occured!");
            }
            $result = $query->fetchAll();
            
            if ($result) {
                return $result;
            } else {
                throw new Exception("No Profile results found");
            }

        } catch (Exception $r) {
            return ['success' => false,'message' => $r->getMessage()];
        }
    }


}


?>