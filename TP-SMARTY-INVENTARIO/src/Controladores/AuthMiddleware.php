<?php

namespace App\Controladores;

class AuthMiddleware
{
    public static function requireLogin()
    {
        // session_start() is already called in bootstrap.php
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit();
        }
    }

    public static function requireAdmin()
    {
        self::requireLogin(); // First, ensure the user is logged in
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            // Redirect to home or show an unauthorized error
            header('Location: ' . BASE_URL); // Redirect to home page
            exit();
        }
    }
    public static function requireSupervisor()
    {
        self::requireLogin(); // First, ensure the user is logged in
        // Check if role is 'supervisor' or 'admin'
        if (!isset($_SESSION['role']) || (!in_array($_SESSION['role'], ['supervisor', 'admin']))) {
            // Redirect to home or show an unauthorized error
            header('Location: ' . BASE_URL); // Redirect to home page
            exit();
        }
    }

    public static function requireAdminOrSupervisor()
    {
        // Ensure the user is logged in first
        self::requireLogin();

        // Allow either 'admin' or 'supervisor'
        if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'supervisor'])) {
            header('Location: ' . BASE_URL);
            exit();
        }
    }
}
