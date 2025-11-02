<?php

class AuthMiddleware {
    public static function requireLogin() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit();
        }
    }
}

?>