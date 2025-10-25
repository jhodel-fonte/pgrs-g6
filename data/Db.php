<?php

class Database { //Database Connection
    private $servername = "localhost";
    private $username = "pgsys_admin";
    private $password = "test";
    private $database = "unity_pgsys_db";
    private $conn;

    function __construct() {//test connection
        try {
            $this->conn = mysqli_connect($this->servername, $this->username, $this->password, $this->database);

            if (!$this->conn) {
                throw new Exception("Connection failed: " . mysqli_connect_error());
            }
            echo "<script>console.log('Connected Database!')</script>";
         
        } catch (Exception $error) {
            error_log(date('[Y-m-d H:i:s] ') . $error->getMessage() . PHP_EOL, 3, __DIR__ . '/../log/database.log');
            die("Database Connection Error!");
        }

    }

    function getConn(){
        return $this->conn;
    }

}
?>