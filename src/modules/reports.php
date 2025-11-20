<?php

require_once __DIR__ .'./../data/Db.php';

function getAllReports(){
    $mysqli = new Database();
    $conn = $mysqli->getConn();
    
    $query = "SELECT * FROM `reports`";
    $result = $conn->query($query);

    if ($result) {
        $reports = [];
        while ($row = $result->fetch_assoc()) {
            $reports[] = $row;
        }
        $result->free();
        
        var_dump($reports);
    } else {
        echo "Query failed: " . $conn->error;
    }

    $conn->close();
}

?>