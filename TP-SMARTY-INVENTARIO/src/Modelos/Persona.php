<?php

namespace App\Modelos; // Add this line

abstract class Persona
{
    protected ?int $id;
    protected string $nombre;
    protected ?string $username;
    protected ?string $password;
    protected string $role;

    public function __construct(?int $id, string $nombre, ?string $username, ?string $password, string $role)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }
}
