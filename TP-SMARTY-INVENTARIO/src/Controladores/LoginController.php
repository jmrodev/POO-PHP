<?php

namespace App\Controladores;

use App\Repositories\PersonaRepository;
use Smarty;

use App\Services\AuthService; // Add this use statement

class LoginController extends BaseController
{
    private PersonaRepository $personaRepository;
    private AuthService $authService; // Add this property

    public function __construct(Smarty $smarty, PersonaRepository $personaRepository, AuthService $authService)
    {
        parent::__construct($smarty);
        $this->personaRepository = $personaRepository;
        $this->authService = $authService; // Assign the service
    }

    public function showLoginForm(): void
    {
        $this->smarty->assign('page_title', 'Iniciar Sesión');
        $this->smarty->display('login.tpl');
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($this->authService->login($username, $password)) {
                $_SESSION['success_message'] = '¡Bienvenido, ' . $this->authService->getUsername() . '!'; // Assuming getUsername() is added to AuthService
                $this->redirect(BASE_URL);
            } else {
                $_SESSION['error_message'] = 'Usuario o contraseña incorrectos.';
                $this->showLoginForm();
            }
        } else {
            $this->showLoginForm();
        }
    }

    public function logout(): void
    {
        $this->authService->logout();
        $_SESSION['success_message'] = 'Has cerrado sesión correctamente.';
        header('Location: ' . BASE_URL . 'login');
        exit();
    }
}