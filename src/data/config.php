<?php
// config/config.php
// Default local settings (XAMPP)
$host = 'localhost';
$dbname = 'unity_pgsys_db';
$username = 'root';
$password = '';

// $host = "sql213.infinityfree.com";
// $username = "if0_40422080";
// $password = "rrLrWJCO9rp";
// $dbname = "if0_40422080_unity_pgsys_db";

// If a Database class (Aiven) exists, prefer it (it handles SSL options). Otherwise, use local PDO.
if (file_exists(__DIR__ . '/Db.php')) {
    try {
        require_once __DIR__ . '/Db.php';
        $db = new Database();
        $pdo = $db->getConn();
    } catch (Exception $e) {
        error_log(date('[Y-m-d H:i:s] ') . 'Aiven DB init failed: ' . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/../log/database.log');
        // Fallback to local
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e2) {
            error_log(date('[Y-m-d H:i:s] ') . $e2->getMessage() . PHP_EOL, 3, __DIR__ . '/../log/database.log');
            die("Database connection failed: " . $e2->getMessage());
        }
    }
} else {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log(date('[Y-m-d H:i:s] ') . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/../log/database.log');
        die("Database connection failed: " . $e->getMessage());
    }
}
?>
