<?php

// routes/clientes.php

require_once SERVER_PATH . '/src/Controladores/ClienteController.php';

$clienteController = new ClienteController();

// Parse the URL to get action and ID
$url_parts = explode('/', trim($route, '/'));
$action = $url_parts[1] ?? 'index'; // Default to index
$id = $url_parts[2] ?? null;

switch ($action) {
    case 'index':
        $clienteController->index();
        break;
    case 'create':
        $clienteController->create();
        break;
    case 'store':
        $clienteController->store();
        break;
    case 'edit':
        $clienteController->edit($id);
        break;
    case 'update':
        $clienteController->update($id);
        break;
    case 'delete':
        $clienteController->delete($id);
        break;
    default:
        // Handle 404 or redirect to index
        header('Location: ' . BASE_URL . 'clientes');
        exit();
}
