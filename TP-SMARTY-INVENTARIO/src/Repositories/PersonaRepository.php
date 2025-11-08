<?php

namespace App\Repositories;

use App\Modelos\Persona;
use App\Modelos\Usuario;
use App\Modelos\Administrador;

class PersonaRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Persona $persona): bool
    {
        if ($persona->getId() === null) {
            return $this->insert($persona);
        } else {
            return $this->update($persona);
        }
    }

    private function insert(Persona $persona): bool
    {
        $sql = "INSERT INTO personas (nombre, username, password, role, dni) VALUES (:nombre, :username, :password, :role, :dni)";
        $stmt = $this->pdo->prepare($sql);

        $dni = null;
        if ($persona instanceof Usuario) {
            $dni = $persona->getDni();
        }

        $stmt->bindValue(':nombre', $persona->getNombre());
        $stmt->bindValue(':username', $persona->getUsername());
        $stmt->bindValue(':password', $persona->getPassword());
        $stmt->bindValue(':role', $persona->getRole());
        $stmt->bindValue(':dni', $dni);

        try {
            if ($stmt->execute()) {
                $persona->setId((int)$this->pdo->lastInsertId());
                return true;
            }
        } catch (\PDOException $e) {
            // Log the exception or handle it appropriately
            // For now, we'll just return false
        }
        return false;
    }

    private function update(Persona $persona): bool
    {
        $sql = "UPDATE personas SET nombre = :nombre, username = :username, password = :password, role = :role, dni = :dni WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        $dni = null;
        if ($persona instanceof Usuario) {
            $dni = $persona->getDni();
        }

        $stmt->bindValue(':nombre', $persona->getNombre());
        $stmt->bindValue(':username', $persona->getUsername());
        $stmt->bindValue(':password', $persona->getPassword());
        $stmt->bindValue(':role', $persona->getRole());
        $stmt->bindValue(':dni', $dni);
        $stmt->bindValue(':id', $persona->getId());

        return $stmt->execute();
    }

    public function findByUsername(string $username): ?Persona
    {
        $stmt = $this->pdo->prepare("SELECT * FROM personas WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return $this->createPersonaFromData($data);
    }

    public function findById(int $id): ?Persona
    {
        $stmt = $this->pdo->prepare("SELECT * FROM personas WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return $this->createPersonaFromData($data);
    }

    private function createPersonaFromData(array $data): Persona
    {
        if ($data['role'] === 'client' || $data['role'] === 'supervisor' || $data['role'] === 'user') { // Handle supervisor and user roles
            return new Usuario(
                $data['id'],
                $data['nombre'],
                $data['username'],
                $data['password'],
                $data['dni'],
                $data['role'] // Pass the role to the Usuario constructor
            );
        } elseif ($data['role'] === 'admin') {
            return new Administrador(
                $data['id'],
                $data['nombre'],
                $data['username'],
                $data['password']
            );
        }
        // Fallback or throw an exception for unknown roles
        throw new \Exception("Unknown persona role: " . $data['role']);
    }

    public function usernameExists(string $username): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM personas WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function dniExists(string $dni): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM personas WHERE dni = :dni");
        $stmt->bindParam(':dni', $dni);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function getAllPersonas(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM personas"); // Removed WHERE role = 'client'
        $stmt->execute();
        $personasData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $personas = [];
        foreach ($personasData as $data) {
            $personas[] = $this->createPersonaFromData($data);
        }
        return $personas;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM personas WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getAllUsers(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM personas WHERE role = 'user' OR role = 'supervisor'");
        $stmt->execute();
        $usersData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $users = [];
        foreach ($usersData as $data) {
            $users[] = $this->createPersonaFromData($data);
        }
        return $users;
    }
}
