<?php

// include_once __DIR__ ."../../../data/Db.php";

function getTableRows($dbConn, $table) {
    $query = $dbConn->prepare("SHOW COLUMNS FROM " .$table .";");
    $query->execute();
    // $query->bind_param("s", $table);
    $result = $query->get_result();

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $temp[] = $row['Field'];
        }
    }

    return $temp;
}

// $db = new Database();

// var_dump(getTableRows($db->getConn(), "account"));


?>
