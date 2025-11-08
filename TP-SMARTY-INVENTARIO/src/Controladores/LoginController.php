<?php

namespace App\Controladores;

use App\Repositories\PersonaRepository;
use Smarty;

class LoginController extends BaseController
{
    private PersonaRepository $personaRepository;

    public function __construct(\Smarty $smarty, PersonaRepository $personaRepository)
    {
        parent::__construct($smarty);
        $this->personaRepository = $personaRepository;
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

            $persona = $this->personaRepository->findByUsername($username);

            if ($persona && password_verify($password, $persona->getPassword())) {
                session_start();
                $_SESSION['user_id'] = $persona->getId();
                $_SESSION['username'] = $persona->getUsername();
                $_SESSION['role'] = $persona->getRole();
                header('Location: /');
                exit();
            } else {
                $this->smarty->assign('error_message', 'Usuario o contraseña incorrectos.');
            }
        }
        $this->showLoginForm();
    }

    public function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /login');
        exit();
    }
}