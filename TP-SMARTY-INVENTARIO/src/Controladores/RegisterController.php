<?php

namespace App\Controladores;

use App\Modelos\Usuario;
use App\Repositories\PersonaRepository;
use Smarty;

use App
Services
AuthService; // Add this use statement

class RegisterController extends BaseController
{
    private PersonaRepository $personaRepository;
    private AuthService $authService; // Add this property

    public function __construct(Smarty $smarty, PersonaRepository $personaRepository, AuthService $authService)
    {
        parent::__construct($smarty);
        $this->personaRepository = $personaRepository;
        $this->authService = $authService; // Assign the service
    }

    public function showRegisterForm(): void
    {
        $this->smarty->assign('page_title', 'Registrarse');
        $this->smarty->display('register.tpl');
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $dni = $_POST['dni'] ?? '';

            // Store form data to re-populate form on error
            $formData = [
                'nombre' => $nombre,
                'username' => $username,
                'dni' => $dni,
            ];
            $this->smarty->assign('form_data', $formData);

            // Basic validation
            if (empty($nombre) || empty($username) || empty($password) || empty($confirm_password) || empty($dni)) {
                $this->smarty->assign('error_message', 'Todos los campos son obligatorios.');
                $this->showRegisterForm();
                return;
            }

            if ($password !== $confirm_password) {
                $this->smarty->assign('error_message', 'Las contraseñas no coinciden.');
                $this->showRegisterForm();
                return;
            }

            if ($this->personaRepository->usernameExists($username)) {
                $this->smarty->assign('error_message', 'El nombre de usuario ya existe.');
                $this->showRegisterForm();
                return;
            }

            if ($this->personaRepository->dniExists($dni)) {
                $this->smarty->assign('error_message', 'El DNI ya está registrado.');
                $this->showRegisterForm();
                return;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $usuario = new Usuario(null, $nombre, $username, $hashedPassword, $dni);

        if ($this->personaRepository->save($usuario)) {
            $_SESSION['message'] = 'Registro exitoso. Por favor, inicia sesión.';
            $_SESSION['message_type'] = 'success';
            $this->redirect(BASE_URL . 'login');
        } else {
            $_SESSION['message'] = 'Error en el registro. Inténtalo de nuevo.';
            $_SESSION['message_type'] = 'error';
            $this->redirect(BASE_URL . 'register');
        }
        }
        $this->showRegisterForm();
    }
}