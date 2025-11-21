<?php

class Database { //Database Connection
    private $servername = "localhost";
    private $username = "pgsys_admin";
    private $password = "test";
    private $database = "unity_pgsys_db";

    // private $servername = "sql213.infinityfree.com";
    // private $username = "if0_40422080";
    // private $password = "rrLrWJCO9rp";
    // private $database = "if0_40422080_unity_pgsys_db";
    private $conn;

    function __construct() {//test connection
        try {
            $dsn = "mysql:host={$this->servername};dbname={$this->database};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            // echo "<script>console.log('Connected Database!')</script>";
            return $this->conn;
         
        } catch (PDOException $error) {
            error_log(date('[Y-m-d H:i:s] ') . $error->getMessage() . PHP_EOL, 3, __DIR__ . '/../log/database.log');
            die("Database Connection Error!" ."<script>console.log('Database Connection Error! Check Log For details')</script>");
        }

    }

    function getConn(){
        return $this->conn;
    }

}
?>