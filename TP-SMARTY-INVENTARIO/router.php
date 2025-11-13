<?php

$container = require_once 'bootstrap.php';

$smarty = $container['smarty'];
$authService = $container['authService'];
$loginController = $container['loginController'];
$registerController = $container['registerController'];
$usuarioController = $container['usuarioController'];
$repuestoController = $container['repuestoController'];
$ventaController = $container['ventaController'];
$cartController = $container['cartController'];
$pedidoController = $container['pedidoController'];
$personaRepository = $container['personaRepository']; // Needed for home route
$router = $container['router']; // Get the router instance

// Define middleware
$router->addMiddleware('admin', function () use ($authService, $smarty) {
    if (!$authService->isAdmin()) {
        header('Location: ' . BASE_URL . 'login');
        exit();
    }
});

$router->addMiddleware('login', function () use ($authService, $smarty) {
    if (!$authService->isLoggedIn()) {
        header('Location: ' . BASE_URL . 'login');
        exit();
    }
});

$router->addMiddleware('user', function () use ($authService, $smarty) {
    if (!$authService->isUser()) {
        header('Location: ' . BASE_URL . 'login');
        exit();
    }
});

$router->addMiddleware('supervisor', function () use ($authService, $smarty) {
    if (!$authService->isSupervisor()) {
        header('Location: ' . BASE_URL . 'login');
        exit();
    }
});

$router->addMiddleware('user_or_supervisor', function () use ($authService, $smarty) {
    if (!$authService->isUser() && !$authService->isSupervisor()) {
        header('Location: ' . BASE_URL . 'login');
        exit();
    }
});

// Define routes
require_once __DIR__ . '/routes/home.php';
require_once __DIR__ . '/routes/auth.php';
require_once __DIR__ . '/routes/usuarios.php';
require_once __DIR__ . '/routes/repuestos.php';
require_once __DIR__ . '/routes/ventas.php';
require_once __DIR__ . '/routes/cart.php';
require_once __DIR__ . '/routes/pedidos.php';

// Dispatch the request
$router->dispatch($smarty);

// If dispatch doesn't find a route, it will handle the 404.
// No need for the old 404 handling here.