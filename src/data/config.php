<?php
// config/config.php
$host = 'localhost';
$dbname = 'padre_garcia_reports';
$username = 'root'; // default for XAMPP
$password = '';     // leave blank unless you set one

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
