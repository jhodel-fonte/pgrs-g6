<?php

require_once __DIR__ .'Db.php';

function getAllReports($page) {
    $mysqli = new Database();
    $conn = $mysqli->getConn();
    
    $query = "SELECT * FROM reports JOIN profile ON reports.user_id = profile.userId";

    if ($stmt = $conn->prepare($query)) {

        $stmt->execute();
        $result = $stmt->get_result();

        $reports = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $reports[] = $row;
            }
        }
        
        $result->free(); 
        $stmt->close();
        var_dump($reports);
        return $reports;

    } else {
        echo "Query failed: " . $conn->error;
        return false;
    }

}

?>