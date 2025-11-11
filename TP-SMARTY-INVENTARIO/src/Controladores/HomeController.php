<?php

namespace App\Controladores;

use App\Services\AuthService;
use App\Repositories\PersonaRepository;
use Smarty;

class HomeController
{
    private Smarty $smarty;
    private PersonaRepository $personaRepository;
    private AuthService $authService;
    private LoginController $loginController;

    public function __construct(Smarty $smarty, PersonaRepository $personaRepository, AuthService $authService, LoginController $loginController)
    {
        $this->smarty = $smarty;
        $this->personaRepository = $personaRepository;
        $this->authService = $authService;
        $this->loginController = $loginController;
    }

    public function showHome(): void
    {
        if (!$this->authService->isLoggedIn()) {
            $this->loginController->showLoginForm();
            return;
        }

        if ($this->authService->isUser()) {
            header('Location: ' . BASE_URL . 'catalog');
            exit();
        } else {
            $userSummary = [];
            if ($this->authService->isAdmin()) {
                $allPersonas = $this->personaRepository->getAllPersonas();
                $userSummary['total_users'] = count($allPersonas);
                $userSummary['admin_count'] = count(array_filter($allPersonas, fn($p) => $p->getRole() === 'admin'));
                $userSummary['supervisor_count'] = count(array_filter($allPersonas, fn($p) => $p->getRole() === 'supervisor'));
                $userSummary['client_count'] = count(array_filter($allPersonas, fn($p) => $p->getRole() === 'user' || $p->getRole() === 'client'));
            }
            $this->smarty->assign('user_summary', $userSummary);
            $this->smarty->display('home.tpl');
        }
    }
}