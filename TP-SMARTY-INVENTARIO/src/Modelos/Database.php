<?php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $config = [
            'servername' => 'localhost',
            'username' => 'root',
            'password' => 'jmro1975',
            'dbname' => 'inventarioRepuestos'
        ];

        $dsn = "mysql:host={$config['servername']};dbname={$config['dbname']};charset=utf8mb4";
        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->pdo;
    }
}

?>