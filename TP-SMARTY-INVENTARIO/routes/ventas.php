<?php

// routes/ventas.php

require_once SERVER_PATH . '/src/Controladores/VentaController.php';

$ventaController = new VentaController();

// Parse the URL to get action and ID
$url_parts = explode('/', trim($route, '/'));
$action = $url_parts[1] ?? 'index'; // Default to index
$id = $url_parts[2] ?? null;

switch ($action) {
    case 'index':
        $ventaController->index();
        break;
    case 'create':
        $ventaController->create();
        break;
    case 'store':
        $ventaController->store();
        break;
    case 'edit':
        $ventaController->edit($id);
        break;
    case 'update':
        $ventaController->update($id);
        break;
    case 'delete':
        $ventaController->delete($id);
        break;
    default:
        // Handle 404 or redirect to index
        header('Location: ' . BASE_URL . 'ventas');
        exit();
}
