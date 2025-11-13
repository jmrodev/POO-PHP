<?php

namespace App\Controladores;

use App\Modelos\Usuario;
use App\Repositories\PersonaRepository;
use App\Validators\UsuarioValidator;
use Smarty;

use App\Services\AuthService; // Add this use statement

class UsuarioController extends BaseController
{
    private PersonaRepository $personaRepository;

    public function __construct(Smarty $smarty, PersonaRepository $personaRepository, AuthService $authService)
    {
        parent::__construct($smarty, $authService);
        $this->personaRepository = $personaRepository;
    }

    public function index(): void
    {
        // AuthMiddleware::requireAdmin(); // Replaced by router middleware

        $perPage = 10; // Número de usuarios por página
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($currentPage < 1) {
            $currentPage = 1;
        }

        $totalUsuarios = $this->personaRepository->contarTodos();
        $totalPages = ceil($totalUsuarios / $perPage);

        $usuarios = $this->personaRepository->obtenerPaginado($currentPage, $perPage);
        
        $this->smarty->assign('usuarios', $usuarios);
        $this->smarty->assign('page_title', 'Gestión de Usuarios');
        $this->smarty->assign('currentPage', $currentPage);
        $this->smarty->assign('totalPages', $totalPages);
        $this->smarty->assign('perPage', $perPage);
        $this->smarty->assign('baseURL', BASE_URL . 'usuarios'); // Base URL for pagination links
        $this->smarty->display('usuarios.tpl');
    }

    public function showFormCreate(): void
    {
        // AuthMiddleware::requireAdmin(); // Replaced by router middleware
        $this->smarty->assign('usuario', new Usuario(null, '', '', '', '', 'user')); // Assign an empty Usuario object with default role
        $this->smarty->assign('page_title', 'Crear Usuario');
        $this->smarty->assign('form_action', BASE_URL . 'usuarios/create');
        $this->smarty->assign('form_data', []);
        $this->smarty->assign('is_edit', false);
        $this->smarty->display('form_usuario.tpl');
    }

    public function create(): void
    {
        // AuthMiddleware::requireAdmin(); // Replaced by router middleware
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new UsuarioValidator(); // Instantiate the validator
            $data = [
                'nombre' => $_POST['nombre'] ?? '',
                'username' => $_POST['username'] ?? '',
                'password' => $_POST['password'] ?? '',
                'dni' => $_POST['dni'] ?? '',
                'role' => $_POST['role'] ?? 'user', // Default to 'user' if not provided
            ];

            // Create a Usuario object with submitted data for re-populating the form
            // Password is not passed here as it's hashed later or not needed for display
            $usuarioWithSubmittedData = new Usuario(null, $data['nombre'], $data['username'], '', $data['dni'], $data['role']);

            // Validate role
            if (!in_array($data['role'], ['user', 'supervisor'])) {
                $this->smarty->assign('error_message', 'Rol inválido. Solo se permiten "user" o "supervisor".');
                $this->smarty->assign('form_data', $data);
                $this->smarty->assign('page_title', 'Añadir Usuario');
                $this->smarty->assign('form_action', BASE_URL . 'usuarios/create');
                $this->smarty->assign('is_edit', false);
                $this->smarty->assign('usuario', $usuarioWithSubmittedData);
                $this->smarty->display('form_usuario.tpl'); // Changed from form_cliente.tpl
                return;
            }

            if (!$validator->validate($data)) {
                $this->smarty->assign('error_message', implode(", ", $validator->getErrors()));
                $this->smarty->assign('form_data', $data);
                $this->smarty->assign('page_title', 'Añadir Usuario');
                $this->smarty->assign('form_action', BASE_URL . 'usuarios/create');
                $this->smarty->assign('is_edit', false);
                $this->smarty->assign('usuario', $usuarioWithSubmittedData);
                $this->smarty->display('form_usuario.tpl'); // Changed from form_cliente.tpl
                return;
            }

            // Check for unique username and DNI
            if ($this->personaRepository->usernameExists($data['username'])) {
                $this->smarty->assign('error_message', 'El nombre de usuario ya existe.');
                $this->smarty->assign('form_data', $data);
                $this->smarty->assign('page_title', 'Añadir Usuario');
                $this->smarty->assign('form_action', BASE_URL . 'usuarios/create');
                $this->smarty->assign('is_edit', false);
                $this->smarty->assign('usuario', $usuarioWithSubmittedData);
                $this->smarty->display('form_usuario.tpl'); // Changed from form_cliente.tpl
                return;
            }
            if ($this->personaRepository->dniExists($data['dni'])) {
                $this->smarty->assign('error_message', 'El DNI ya está registrado.');
                $this->smarty->assign('form_data', $data);
                $this->smarty->assign('page_title', 'Añadir Usuario');
                $this->smarty->assign('form_action', BASE_URL . 'usuarios/create');
                $this->smarty->assign('is_edit', false);
                $this->smarty->assign('usuario', $usuarioWithSubmittedData);
                $this->smarty->display('form_usuario.tpl'); // Changed from form_cliente.tpl
                return;
            }

            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $newUsuario = new Usuario(null, $data['nombre'], $data['username'], $hashedPassword, $data['dni'], $data['role']);

            if ($this->personaRepository->save($newUsuario)) {
                $this->redirect(BASE_URL . 'usuarios');
            } else {
                $this->smarty->assign('error_message', 'Error al crear el usuario.');
                $this->smarty->assign('form_data', $data);
                $this->smarty->assign('page_title', 'Añadir Usuario');
                $this->smarty->assign('form_action', BASE_URL . 'usuarios/create');
                $this->smarty->assign('is_edit', false);
                $this->smarty->assign('usuario', $usuarioWithSubmittedData);
                $this->smarty->display('form_usuario.tpl'); // Changed from form_cliente.tpl
            }
        }
    }

    public function showFormEdit(int $id): void
    {
        // AuthMiddleware::requireAdmin(); // Replaced by router middleware
        $usuario = $this->personaRepository->findById($id);
        if (!$usuario) {
            $_SESSION['error_message'] = 'Usuario no encontrado.';
            $this->redirect(BASE_URL . 'usuarios');
            return;
        }
        $this->smarty->assign('usuario', $usuario);
        $this->smarty->assign('page_title', 'Editar Usuario');
        $this->smarty->assign('form_action', BASE_URL . 'usuarios/update');
        $this->smarty->assign('form_data', []);
        $this->smarty->assign('is_edit', true);
        $this->smarty->display('form_usuario.tpl');
    }

    public function update(): void
    {
        // AuthMiddleware::requireAdmin(); // Replaced by router middleware
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            unset($_SESSION['warning_message']); // Clear any previous warning messages
            $validator = new UsuarioValidator(); // Instantiate the validator
            $id = $_POST['id'] ?? null;
            if (!$id) {
                $_SESSION['error_message'] = 'ID de usuario no proporcionado.';
                $this->redirect(BASE_URL . 'usuarios');
                return;
            }

            $data = [
                'id' => (int)$id, // Cast to int
                'nombre' => $_POST['nombre'] ?? '',
                'username' => $_POST['username'] ?? '',
                'password' => $_POST['password'] ?? '',
                'dni' => $_POST['dni'] ?? '',
                'role' => $_POST['role'] ?? 'user',
            ];

            $warningMessages = []; // Initialize array for warning messages

            // Fetch existing usuario to get password if not updated
            $existingUsuario = $this->personaRepository->findById($data['id']);
            if (!$existingUsuario || !($existingUsuario instanceof Usuario)) {
                $this->redirect(BASE_URL . 'usuarios');
                return;
            }

            // If admin, and fields are disabled, they won't be in $_POST.
            // In this case, use the existing user's data for these fields
            // so the validator doesn't complain about emptiness.
            if ($this->authService->isAdmin()) {
                if (!isset($_POST['nombre'])) {
                    $data['nombre'] = $existingUsuario->getNombre();
                }
                if (!isset($_POST['dni'])) {
                    $data['dni'] = $existingUsuario->getDni();
                }
                if (!isset($_POST['username'])) {
                    $data['username'] = $existingUsuario->getUsername();
                }
            }

            // Create a Usuario object with submitted data for re-populating the form
            $usuarioWithSubmittedData = new Usuario($data['id'], $data['nombre'], $data['username'], $existingUsuario->getPassword(), $data['dni'], $data['role']);

            // Validate role
            if (!in_array($data['role'], ['user', 'supervisor'])) {
                $this->smarty->assign('error_message', 'Rol inválido. Solo se permiten "user" o "supervisor".');
                $this->smarty->assign('form_data', $data);
                $this->smarty->assign('page_title', 'Editar Usuario');
                $this->smarty->assign('form_action', BASE_URL . 'usuarios/update');
                $this->smarty->assign('is_edit', true);
                $this->smarty->assign('usuario', $usuarioWithSubmittedData);
                $this->smarty->display('form_usuario.tpl'); // Changed from form_cliente.tpl
                return;
            }

            if (!$validator->validate($data, true)) { // Pass true for isUpdate
                $this->smarty->assign('error_message', implode(", ", $validator->getErrors()));
                $this->smarty->assign('form_data', $data);
                $this->smarty->assign('page_title', 'Editar Usuario');
                $this->smarty->assign('form_action', BASE_URL . 'usuarios/update');
                $this->smarty->assign('is_edit', true);
                $this->smarty->assign('usuario', $usuarioWithSubmittedData);
                $this->smarty->display('form_usuario.tpl'); // Changed from form_cliente.tpl
                return;
            }


            // Check for unique username and DNI if changed
            // These checks should only happen if the fields are actually submitted/editable
            // If the field was disabled, $data will contain the existing value, so this check won't trigger unnecessarily
            if ($existingUsuario->getUsername() !== $data['username'] && $this->personaRepository->usernameExists($data['username'])) {
                $this->smarty->assign('error_message', 'El nombre de usuario ya existe.');
                $this->smarty->assign('form_data', $data);
                $this->smarty->assign('page_title', 'Editar Usuario');
                $this->smarty->assign('form_action', BASE_URL . 'usuarios/update');
                $this->smarty->assign('is_edit', true);
                $this->smarty->assign('usuario', $usuarioWithSubmittedData);
                $this->smarty->display('form_usuario.tpl'); // Changed from form_cliente.tpl
                return;
            }
            if ($existingUsuario->getDni() !== $data['dni'] && $this->personaRepository->dniExists($data['dni'])) {
                $this->smarty->assign('error_message', 'El DNI ya está registrado.'); // Added error message for DNI
                $this->smarty->assign('form_data', $data);
                $this->smarty->assign('page_title', 'Editar Usuario');
                $this->smarty->assign('form_action', BASE_URL . 'usuarios/update');
                $this->smarty->assign('is_edit', true);
                $this->smarty->assign('usuario', $usuarioWithSubmittedData);
                $this->smarty->display('form_usuario.tpl'); // Changed from form_cliente.tpl
                return;
            }

            // Prevent admin from changing their own role
            if ($existingUsuario->getId() === ($_SESSION['user_id'] ?? null) && $data['role'] !== 'admin') {
                $this->smarty->assign('error_message', 'No puedes cambiar tu propio rol de administrador.');
                $this->smarty->assign('form_data', $data);
                $this->smarty->assign('page_title', 'Editar Usuario');
                $this->smarty->assign('form_action', BASE_URL . 'usuarios/update');
                $this->smarty->assign('is_edit', true);
                $this->smarty->assign('usuario', $usuarioWithSubmittedData);
                $this->smarty->display('form_usuario.tpl'); // Changed from form_cliente.tpl
                return;
            }

            // Prevent admin from changing nombre, dni, and username of other users
            if ($this->authService->isAdmin()) {
                if ($existingUsuario->getNombre() !== ($_POST['nombre'] ?? $existingUsuario->getNombre())) {
                    $data['nombre'] = $existingUsuario->getNombre();
                    $warningMessages[] = 'Como administrador, no puedes cambiar el nombre completo del usuario.';
                }
                if ($existingUsuario->getDni() !== ($_POST['dni'] ?? $existingUsuario->getDni())) {
                    $data['dni'] = $existingUsuario->getDni();
                    $warningMessages[] = 'Como administrador, no puedes cambiar el DNI del usuario.';
                }
                if ($existingUsuario->getUsername() !== ($_POST['username'] ?? $existingUsuario->getUsername())) {
                    $data['username'] = $existingUsuario->getUsername();
                    $warningMessages[] = 'Como administrador, no puedes cambiar el nombre de usuario.';
                }
            }

            $usuarioToUpdate = new Usuario(
                $data['id'],
                $data['nombre'],
                $data['username'],
                $existingUsuario->getPassword(), // Keep existing password
                $data['dni'],
                $data['role'] // Pass the role
            );

            if ($this->personaRepository->save($usuarioToUpdate)) {
                if (!empty($warningMessages)) {
                    $_SESSION['warning_message'] = implode(' ', $warningMessages);
                }
                $this->redirect(BASE_URL . 'usuarios');
            } else {
                $this->smarty->assign('error_message', 'Error al actualizar el usuario.');
                $this->smarty->assign('form_data', $data);
                $this->smarty->assign('page_title', 'Editar Usuario');
                $this->smarty->assign('form_action', BASE_URL . 'usuarios/update');
                $this->smarty->assign('is_edit', true);
                $this->smarty->assign('usuario', $usuarioWithSubmittedData);
                $this->smarty->display('form_usuario.tpl'); // Changed from form_cliente.tpl
            }
        }
    }

    public function showConfirmDelete(int $id): void
    {
        // AuthMiddleware::requireAdmin(); // Replaced by router middleware
        $usuario = $this->personaRepository->findById($id);
        if (!$usuario) {
            $_SESSION['error_message'] = 'Usuario no encontrado.';
            $this->redirect(BASE_URL . 'usuarios');
            return;
        }
        $this->smarty->assign('usuario', $usuario);
        $this->smarty->assign('page_title', 'Confirmar Eliminación de Usuario');
        $this->smarty->display('confirm_delete_usuario.tpl');
    }

    public function delete(int $id): void
    {
        // AuthMiddleware::requireAdmin(); // Replaced by router middleware

        if ($this->personaRepository->delete($id)) {
            $this->redirect(BASE_URL . 'usuarios');
        } else {
            $this->redirect(BASE_URL . 'usuarios');
        }
    }
}
