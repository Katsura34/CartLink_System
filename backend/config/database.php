<?php
/**
 * Database Configuration and Connection Handler
 * CartLink System - MySQL Database Connection
 */

class Database {
    // Database credentials
    private $host = "localhost";
    private $db_name = "cartlink_db";
    private $username = "root";
    private $password = "";
    private $charset = "utf8mb4";
    
    public $conn;
    
    /**
     * Establish database connection
     * @return PDO|null
     */
    public function getConnection() {
        $this->conn = null;
        
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch(PDOException $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Connection Error: ' . $e->getMessage()
            ]);
            die();
        }
        
        return $this->conn;
    }
}
?>
