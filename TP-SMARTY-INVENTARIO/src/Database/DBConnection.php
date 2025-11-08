<?php

namespace App\Database;

use PDO;
use PDOException;
use RuntimeException;

class DBConnection
{
    private static $instance = null;
    private $conn;

    private $host;
    private $db_name;
    private $username;
    private $password;

    private function __construct()
    {
        $this->host = $_ENV['DB_HOST'] ?? 'localhost';
        $this->db_name = $_ENV['DB_NAME'] ?? 'inventarioRepuestos';
        $this->username = $_ENV['DB_USER'] ?? 'root';
        $this->password = $_ENV['DB_PASSWORD'] ?? '';

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            throw new RuntimeException("No se pudo conectar a la base de datos. Por favor, contacte al administrador.");
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
