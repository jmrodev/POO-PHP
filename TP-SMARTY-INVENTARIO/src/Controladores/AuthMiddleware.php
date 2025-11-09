<?php

namespace App\Controladores;

// This class is being refactored to use AuthService directly in router.php
// Its content is commented out to prevent conflicts during the transition.
// It might be removed or repurposed later.

/*
class AuthMiddleware
{
    public static function requireLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit();
        }
    }

    public static function requireAdmin()
    {
        self::requireLogin(); // First, ensure the user is logged in
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . 'home'); // Redirect to home or an unauthorized page
            exit();
        }
    }

    public static function requireSupervisor()
    {
        self::requireLogin(); // First, ensure the user is logged in
        // Check if role is 'supervisor' or 'admin'
        if (!isset($_SESSION['role']) || (!in_array($_SESSION['role'], ['supervisor', 'admin']))) {
            header('Location: ' . BASE_URL . 'home'); // Redirect to home or an unauthorized page
            exit();
        }
    }

    public static function requireAdminOrSupervisor()
    {
        // Ensure the user is logged in first
        self::requireLogin();
        // Check if role is 'admin' or 'supervisor'
        if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'supervisor'])) {
            header('Location: ' . BASE_URL . 'home'); // Redirect to home or an unauthorized page
            exit();
        }
    }

    public static function requireOnlySupervisor()
    {
        self::requireLogin();
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'supervisor') {
            header('Location: ' . BASE_URL . 'home'); // Redirect to home or an unauthorized page
            exit();
        }
    }

    public static function requireUserOnly()
    {
        self::requireLogin();
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
            header('Location: ' . BASE_URL . 'home'); // Redirect to home or an unauthorized page
            exit();
        }
    }
}
*/
