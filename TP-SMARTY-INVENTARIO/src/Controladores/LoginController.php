<?php

include_once SERVER_PATH . '/src/Modelos/User.php';
include_once SERVER_PATH . '/src/Vistas/LoginVista.php';

class LoginController {
    private $userModel;
    private $loginVista;

    public function __construct() {
        $this->userModel = new User();
        $this->loginVista = new LoginVista();
    }

    public function showLoginForm($message = null) {
        $this->loginVista->displayLoginForm($message);
    }

    public function authenticate() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->findByUsername($username);

        if ($user && password_verify($password, $user->getPassword())) {
            session_start();
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['username'] = $user->getUsername();
            header('Location: ' . BASE_URL . 'home');
            exit();
        } else {
            $this->showLoginForm("Usuario o contraseña incorrectos.");
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . 'login');
        exit();
    }
}

?>
