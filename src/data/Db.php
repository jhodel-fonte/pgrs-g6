<?php

// $db_link = "";
// $data_source_url = "http://iinfri/request/getData.php?data=members";

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

    function __construct() {
            // Build the DSN (Data Source Name) string for MySQL
            $dsn = "mysql:host={$this->servername};dbname={$this->database};charset=utf8mb4";
            
            try {
                // Create the PDO instance
                $this->conn = new PDO($dsn, $this->username, $this->password);

                // Set PDO attributes for robust error handling and fetching
                // Throws exceptions on errors, which is critical for debugging
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // Sets default fetch mode to associative arrays
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            } catch (PDOException $error) { 
                // Log the error message to a file
                error_log(date('[Y-m-d H:i:s] ') . $error->getMessage() . PHP_EOL, 3, __DIR__ . '/../log/database.log');
                
                // Halt script execution and display a generic connection error
                die("Database Connection Error! <script>console.log('Database Connection Error! Check Log For details')</script>");
            }
        }

        /**
         * @brief Returns the PDO connection object.
         * @return PDO The active PDO connection object.
         */
        function getConn(){
            return $this->conn;
        }
    }
?>