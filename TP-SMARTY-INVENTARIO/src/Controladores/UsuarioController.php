<?php

namespace App\Controladores;

use App\Modelos\Usuario;
use App\Repositories\PersonaRepository;
use App\Validators\UsuarioValidator;
use Smarty;

class UsuarioController extends BaseController
{
    private PersonaRepository $personaRepository;

    public function __construct(\Smarty $smarty, PersonaRepository $personaRepository)
    {
        parent::__construct($smarty);
        $this->personaRepository = $personaRepository;
    }

    public function index(): void
    {
        AuthMiddleware::requireAdmin(); // Require admin role

        $personas = $this->personaRepository->getAllPersonas(); // Call getAllPersonas
        $this->smarty->assign('personas', $personas); // Assign to 'personas'
        $this->smarty->assign('page_title', 'Gestión de Usuarios'); // Changed title
        $this->smarty->display('usuarios.tpl');
    }

    public function showFormCreate(): void
    {
        AuthMiddleware::requireAdmin(); // Require admin role

        $this->smarty->assign('page_title', 'Añadir Usuario');
        $this->smarty->assign('form_action', BASE_URL . 'usuarios/create');
        $this->smarty->assign('is_edit', false);
        $this->smarty->assign('usuario', new Usuario(null, '', '', '', '', 'user')); // Assign an empty Usuario object with default role
        $this->smarty->assign('form_data', []); // Always assign empty form_data
        $this->smarty->display('form_usuario.tpl'); // Changed from form_cliente.tpl
    }

    public function create(): void
    {
        AuthMiddleware::requireAdmin(); // Require admin role

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new UsuarioValidator();
            $data = [
                'nombre' => $_POST['nombre'] ?? '',
                'dni' => $_POST['dni'] ?? '',
                'username' => $_POST['username'] ?? '',
                'password' => $_POST['password'] ?? '',
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
        AuthMiddleware::requireAdmin(); // Require admin role

        $usuario = $this->personaRepository->findById($id);
        if (!$usuario || !($usuario instanceof Usuario)) {
            $this->redirect(BASE_URL . 'usuarios');
            return;
        }

        $this->smarty->assign('page_title', 'Editar Usuario');
        $this->smarty->assign('form_action', BASE_URL . 'usuarios/update');
        $this->smarty->assign('is_edit', true);
        $this->smarty->assign('usuario', $usuario);
        $this->smarty->assign('form_data', []); // Always assign empty form_data
        $this->smarty->display('form_usuario.tpl'); // Changed from form_cliente.tpl
    }

    public function update(): void
    {
        AuthMiddleware::requireAdmin(); // Require admin role

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new UsuarioValidator();
            $data = [
                'id' => $_POST['id'] ?? null,
                'nombre' => $_POST['nombre'] ?? '',
                'dni' => $_POST['dni'] ?? '',
                'username' => $_POST['username'] ?? '',
                'role' => $_POST['role'] ?? 'user', // Default to 'user' if not provided
            ];

            // Fetch existing usuario to get password if not updated
            $existingUsuario = $this->personaRepository->findById($data['id']);
            if (!$existingUsuario || !($existingUsuario instanceof Usuario)) {
                $this->redirect(BASE_URL . 'usuarios');
                return;
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
                $this->smarty->assign('error_message', 'El DNI ya está registrado.');
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

            $usuarioToUpdate = new Usuario(
                $data['id'],
                $data['nombre'],
                $data['username'],
                $existingUsuario->getPassword(), // Keep existing password
                $data['dni'],
                $data['role'] // Pass the role
            );

            if ($this->personaRepository->save($usuarioToUpdate)) {
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
        AuthMiddleware::requireAdmin(); // Require admin role

        $usuario = $this->personaRepository->findById($id);
        if (!$usuario || !($usuario instanceof Usuario)) {
            $this->redirect(BASE_URL . 'usuarios');
            return;
        }

        $this->smarty->assign('page_title', 'Confirmar Eliminación');
        $this->smarty->assign('usuario', $usuario);
        $this->smarty->assign('form_data', []); // Always assign empty form_data
        $this->smarty->display('confirm_delete_usuario.tpl'); // Changed from confirm_delete_cliente.tpl
    }

    public function delete(int $id): void
    {
        AuthMiddleware::requireAdmin(); // Require admin role

        if ($this->personaRepository->delete($id)) {
            $this->redirect(BASE_URL . 'usuarios');
        } else {
            $this->redirect(BASE_URL . 'usuarios');
        }
    }
}
