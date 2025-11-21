<?php

require_once __DIR__ .'../Db.php';

function getAllReports($page) {
    $db = new Database();
    $conn = $db->getConn();
    
    $query = "SELECT * FROM reports JOIN profile ON reports.user_id = profile.userId";

    try {
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $reports = $stmt->fetchAll();
        
        return $reports;

    } catch (PDOException $e) {
        error_log("Query failed: " . $e->getMessage());
        return false;
    }

}

?>