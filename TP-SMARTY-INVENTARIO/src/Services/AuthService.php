<?php

namespace App\Services;

use App\Repositories\PersonaRepository;
use App\Modelos\Persona; // Assuming Persona model is used for user objects

class AuthService
{
    private PersonaRepository $personaRepository;

    public function __construct(PersonaRepository $personaRepository)
    {
        $this->personaRepository = $personaRepository;
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login(string $username, string $password): bool
    {
        $persona = $this->personaRepository->findByUsername($username);

        if ($persona && password_verify($password, $persona->getPassword())) {
            $_SESSION['user_id'] = $persona->getId();
            $_SESSION['username'] = $persona->getUsername();
            $_SESSION['role'] = $persona->getRole();
            return true;
        }
        return false;
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        $_SESSION = []; // Ensure $_SESSION is empty
    }

    public function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public function getUserId(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    public function getUserRole(): ?string
    {
        return $_SESSION['role'] ?? null;
    }

    public function isAdmin(): bool
    {
        return $this->getUserRole() === 'admin';
    }

    public function isSupervisor(): bool
    {
        return $this->getUserRole() === 'supervisor';
    }

    public function isUser(): bool
    {
        return $this->getUserRole() === 'user';
    }

    public function getUsername(): ?string
    {
        return $_SESSION['username'] ?? null;
    }

    // Potentially add methods for more granular permissions later
    // public function can(string $permission): bool { ... }
}
