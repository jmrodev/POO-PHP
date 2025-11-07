<?php

// routes/repuestos.php

require_once SERVER_PATH . '/src/Controladores/RepuestoController.php';

$repuestoController = new RepuestoController();

// Parse the URL to get action and ID
$url_parts = explode('/', trim($route, '/'));
$action = $url_parts[1] ?? 'index'; // Default to index
$id = $url_parts[2] ?? null;

switch ($action) {
    case 'index':
        $repuestoController->index();
        break;
    case 'create':
        $repuestoController->create();
        break;
    case 'store':
        $repuestoController->store();
        break;
    case 'edit':
        $repuestoController->edit($id);
        break;
    case 'update':
        $repuestoController->update($id);
        break;
    case 'delete':
        $repuestoController->delete($id);
        break;
    default:
        // Handle 404 or redirect to index
        header('Location: ' . BASE_URL . 'repuestos');
        exit();
}
