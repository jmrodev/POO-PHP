<?php

class DBConnection
{
    private static $instance = null;
    private $conn;

    private $host = 'localhost';
    private $db_name = 'inventarioRepuestos';
    private $username = 'root';
    private $password = 'jmro1975';

    private function __construct()
    {
        try {
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Connection Error: ' . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new DBConnection();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }
}

// Example usage (can be removed later if not needed directly here)
// $db = DBConnection::getInstance();
// $pdo = $db->getConnection();
