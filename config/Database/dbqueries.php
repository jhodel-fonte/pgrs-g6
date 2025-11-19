<?php

require_once __DIR__ .'/db.php';

class DbQueries {
    private $conn;
    private $logDir = __DIR__ . '/../../logs/';

    public function __construct() {
        try {
            $database = new Database();
            $this->conn = $database->getConn();
            
        } catch (Exception $e) {
            $this->logError('Constructor Error', $e->getMessage());
            throw new Exception('Database connection failed');
        }
    }

    // Check for duplicate username, email, or mobile
    public function checkDuplicateUser($username, $email, $mobile) {
        try {
            $query = "SELECT * FROM account WHERE username = ? OR email = ? OR mobileNum = ?";
            $stmt = $this->conn->prepare($query);
            
            if (!$stmt) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }
            
            $stmt->bind_param("sss", $username, $email, $mobile);
            
            if (!$stmt->execute()) {
                throw new Exception('Execute failed: ' . $stmt->error);
            }
            
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            $this->logError('checkDuplicateUser', $e->getMessage());
            return false;
        }
    }

    // Insert new user
    public function insertUser($data) {
        try {
            $query = "INSERT INTO users (firstname, lastname, mobile_number, email, gender, dob, address, username, password, profile_pic, id_doc) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            
            if (!$stmt) {
                throw new Exception('Prepare failed: ' . $this->conn->error);
            }
            
            $stmt->bind_param(
                "sssssssssss",
                $data['firstname'],
                $data['lastname'],
                $data['mobile_number'],
                $data['email'],
                $data['gender'],
                $data['dob'],
                $data['address'],
                $data['username'],
                $data['password'],
                $data['profile_pic'],
                $data['id_doc']
            );
            
            if (!$stmt->execute()) {
                throw new Exception('Execute failed: ' . $stmt->error);
            }
            
            return true;
        } catch (Exception $e) {
            $this->logError('insertUser', $e->getMessage());
            return false;
        }
    }

    // Log errors to file
    private function logError($functionName, $errorMessage) {
        try {
            $logFile = $this->logDir . 'error_' . date('Y-m-d') . '.log';
            $logMessage = '[' . date('Y-m-d H:i:s') . '] Function: ' . $functionName . ' | Error: ' . $errorMessage . PHP_EOL;
            file_put_contents($logFile, $logMessage, FILE_APPEND);
        } catch (Exception $e) {
            error_log('Failed to write to log file: ' . $e->getMessage());
        }
    }
}
?>