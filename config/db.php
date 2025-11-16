<?php
// config/db.php

   class Database { 
       private $servername = "localhost";
       private $username = "pgsys_admin";
       private $password = "test";
       private $database = "padre_garcia_reporting";
       private $charset = "utf8mb4";
       private $conn;

    //   class Database { 
    //   private $servername = "sql202.infinityfree.com";
    //   private $username = "if0_40421960";
    //   private $password = "eDcJC8VSi1N70nX";
    //   private $database = "if0_40421960_pgsrs";
    //   private $charset = "utf8mb4";
    //   private $conn;

    public function __construct() {
        $dsn = "mysql:host={$this->servername};dbname={$this->database};charset={$this->charset}";
        try {
            // Create PDO connection
            $this->conn = new PDO($dsn, $this->username, $this->password);
            // Set PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Optional: debug
            echo "<script>console.log('Connected to Database via PDO!');</script>";

        } catch (PDOException $e) {
            // Log error to file
            error_log(date('[Y-m-d H:i:s] ') . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/../log/database.log');
            die("Database Connection Error! Check Log For details");
        }
    }

    // Getter for PDO connection
    public function getConn() {
        return $this->conn;
    }
}

// Instantiate Database and create $pdo variable for global use
$database = new Database();
$pdo = $database->getConn();
?>
