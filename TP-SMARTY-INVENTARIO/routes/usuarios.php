<?php

// routes/usuarios.php

require_once SERVER_PATH . '/src/Controladores/UsuarioController.php';

$usuarioController = new UsuarioController();

// Parse the URL to get action and ID
$url_parts = explode('/', trim($route, '/'));
$action = $url_parts[1] ?? 'index'; // Default to index
$id = $url_parts[2] ?? null;

switch ($action) {
    case 'index':
        $usuarioController->index();
        break;
    case 'create':
        $usuarioController->create();
        break;
    case 'store':
        $usuarioController->store();
        break;
    case 'edit':
        $usuarioController->edit($id);
        break;
    case 'update':
        $usuarioController->update($id);
        break;
    case 'delete':
        $usuarioController->delete($id);
        break;
    default:
        // Handle 404 or redirect to index
        header('Location: ' . BASE_URL . 'usuarios');
        exit();
}
