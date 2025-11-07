<?php

class User
{
    private $id;
    private $username;
    private $password;

    public function __construct($id = null, $username = null, $password = null)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function findByUsername($username)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id, username, password FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return new User($data['id'], $data['username'], $data['password']);
        }
        return null;
    }

    public function save()
    {
        $db = Database::getInstance()->getConnection();
        if ($this->id === null) {
            $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':password', $this->password);
            $result = $stmt->execute();
            if ($result) {
                $this->id = $db->lastInsertId();
            }
            return $result;
        } else {
            $stmt = $db->prepare("UPDATE users SET username = :username, password = :password WHERE id = :id");
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':password', $this->password);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            return $stmt->execute();
        }
    }
}
