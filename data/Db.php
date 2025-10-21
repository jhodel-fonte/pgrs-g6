<?php

class Database {
    private $servername = "localhost";
    private $username = "username";
    private $password = "password";
    private $conn;

    function __construct() {//test connection
        try {
            $this->conn = new PDO("mysql:host=$this->servername;dbname=myDB", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
        } catch (PDOException $err) {
            echo "Connection Lost:" .$err->getMessage();
            // papalitan ito kung saan mag nonotif 
            //  bastsa wag sa user papakita details
        }
    }

    function getConn(){
        return $this->conn;
    }

}

$test = new Database();
?>