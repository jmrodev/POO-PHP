<?php

class Database
{
    private static $instance = null;
    private $connection;

    private $servername = "localhost";
    private $username = "root";
    private $password = "jmro1975";
    private $dbname = "inventarioRepuestos";

    private function __construct()
    {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->servername};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
