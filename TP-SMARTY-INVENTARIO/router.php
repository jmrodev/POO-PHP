<?php

// Let the built-in PHP server serve existing static files (css, js, images)
// so requests for resources under the document root are not routed through this script.
if (php_sapi_name() === 'cli-server') {
    $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $file = __DIR__ . $urlPath;
    if (is_file($file)) {
        // Return false to let the built-in web server serve the requested resource
        return false;
    }
}

$container = require_once 'bootstrap.php';

$smarty = $container['smarty'];
$loginController = $container['loginController'];
$registerController = $container['registerController'];
$usuarioController = $container['usuarioController'];
$repuestoController = $container['repuestoController']; // Inject RepuestoController
$ventaController = $container['ventaController']; // Inject VentaController
$cartController = $container['cartController']; // Inject CartController
$pedidoController = $container['pedidoController']; // Inject PedidoController

$request_uri = $_SERVER['REQUEST_URI'];
$script_name = $_SERVER['SCRIPT_NAME'];
$base_path = str_replace(basename($script_name), '', $script_name);

// Correctly extract the route path
$path = parse_url($request_uri, PHP_URL_PATH);
$route = substr($path, strlen($base_path));
$route = strtok($route, '?'); // Remove query string

// Ensure the route starts with a '/' if it's not empty
if (!empty($route) && $route[0] !== '/') {
    $route = '/' . $route;
}

if (empty($route)) {
    $route = '/';
}

// Debugging output
error_log("REQUEST_URI: " . $request_uri);
error_log("SCRIPT_NAME: " . $script_name);
error_log("BASE_PATH: " . $base_path);
error_log("Calculated Route: " . $route);

$http_method = $_SERVER['REQUEST_METHOD'];

// Define routes with HTTP methods and optional middleware
$routes = [
    '/' => ['GET' => ['handler' => function () use ($smarty) {
        $smarty->display('home.tpl');
    }]],
    '/home' => ['GET' => ['handler' => function () use ($smarty) {
        $smarty->display('home.tpl');
    }]],
    '/login' => [
        'GET' => ['handler' => function () use ($loginController) {
            $loginController->showLoginForm();
        }],
        'POST' => ['handler' => function () use ($loginController) {
            $loginController->login();
        }]
    ],
    '/logout' => ['GET' => ['handler' => function () use ($loginController) {
        $loginController->logout();
    }]],
    '/register' => [
        'GET' => ['handler' => function () use ($registerController) {
            $registerController->showRegisterForm();
        }],
        'POST' => ['handler' => function () use ($registerController) {
            $registerController->register();
        }]
    ],
    '/usuarios' => [ // Changed from /clientes
        'GET' => ['handler' => function () use ($usuarioController) {
            $usuarioController->index();
        }, 'middleware' => ['admin']], // Apply 'admin' middleware
    ],
    '/usuarios/add' => [ // Changed from /clientes/add
        'GET' => ['handler' => function () use ($usuarioController) {
            $usuarioController->showFormCreate();
        }, 'middleware' => ['admin']], // Apply 'admin' middleware
    ],
    '/usuarios/create' => [ // Changed from /clientes/create
        'POST' => ['handler' => function () use ($usuarioController) {
            $usuarioController->create();
        }, 'middleware' => ['admin']], // Apply 'admin' middleware
    ],
    '/usuarios/edit/{id}' => [ // Changed from /clientes/edit/{id}
        'GET' => ['handler' => function ($id) use ($usuarioController) {
            $usuarioController->showFormEdit($id);
        }, 'middleware' => ['admin']], // Apply 'admin' middleware
    ],
    '/usuarios/update' => [ // Changed from /clientes/update
        'POST' => ['handler' => function () use ($usuarioController) {
            $usuarioController->update();
        }, 'middleware' => ['admin']], // Apply 'admin' middleware
    ],
    '/usuarios/delete/{id}' => [ // Changed from /clientes/delete/{id}
        'GET' => ['handler' => function ($id) use ($usuarioController) {
            $usuarioController->showConfirmDelete($id);
        }, 'middleware' => ['admin']], // Apply 'admin' middleware
    ],
    '/usuarios/delete_confirm/{id}' => [ // Changed from /clientes/delete_confirm/{id}
        'POST' => ['handler' => function ($id) use ($usuarioController) {
            $usuarioController->delete($id);
        }, 'middleware' => ['admin']], // Apply 'admin' middleware
    ],
    '/repuestos' => [
        'GET' => ['handler' => function () use ($repuestoController) {
            $repuestoController->index();
        }, 'middleware' => ['supervisor']],
    ],
    '/repuestos/add' => [
        'GET' => ['handler' => function () use ($repuestoController) {
            $repuestoController->showFormCreate();
        }, 'middleware' => ['supervisor']],
    ],
    '/repuestos/create' => [
        'POST' => ['handler' => function () use ($repuestoController) {
            $repuestoController->create();
        }, 'middleware' => ['supervisor']],
    ],
    '/repuestos/edit/{id}' => [
        'GET' => ['handler' => function ($id) use ($repuestoController) {
            $repuestoController->showFormEdit($id);
        }, 'middleware' => ['supervisor']],
    ],
    '/repuestos/update' => [
        'POST' => ['handler' => function () use ($repuestoController) {
            $repuestoController->update();
        }, 'middleware' => ['supervisor']],
    ],
    '/repuestos/delete/{id}' => [
        'GET' => ['handler' => function ($id) use ($repuestoController) {
            $repuestoController->showConfirmDelete($id);
        }, 'middleware' => ['supervisor']],
    ],
    '/repuestos/delete_confirm/{id}' => [
        'POST' => ['handler' => function ($id) use ($repuestoController) {
            $repuestoController->delete($id);
        }, 'middleware' => ['supervisor']],
    ],
    '/repuestos/detail/{id}' => [
        'GET' => ['handler' => function ($id) use ($repuestoController) {
            $repuestoController->showDetail($id);
        }, 'middleware' => ['supervisor']],
    ],
    '/ventas' => [
        'GET' => ['handler' => function () use ($ventaController) {
            $ventaController->index();
        }, 'middleware' => ['onlysupervisor']], // Only supervisors can see legacy sales
    ],
    '/ventas/add' => [
        'GET' => ['handler' => function () use ($ventaController) {
            $ventaController->showFormCreate();
        }, 'middleware' => ['onlysupervisor']], // Only supervisors can create legacy sales
    ],
    '/ventas/create' => [
        'POST' => ['handler' => function () use ($ventaController) {
            $ventaController->create();
        }, 'middleware' => ['onlysupervisor']], // Only supervisors can create legacy sales
    ],
    '/ventas/edit/{id}' => [
        'GET' => ['handler' => function ($id) use ($ventaController) {
            $ventaController->showFormEdit($id);
        }, 'middleware' => ['onlysupervisor']], // Only supervisors can edit legacy sales
    ],
    '/ventas/update' => [
        'POST' => ['handler' => function () use ($ventaController) {
            $ventaController->update();
        }, 'middleware' => ['onlysupervisor']], // Only supervisors can update legacy sales
    ],
    '/ventas/delete/{id}' => [
        'GET' => ['handler' => function ($id) use ($ventaController) {
            $ventaController->showConfirmDelete($id);
        }, 'middleware' => ['onlysupervisor']], // Only supervisors can delete legacy sales
    ],
    '/ventas/delete_confirm/{id}' => [
        'POST' => ['handler' => function ($id) use ($ventaController) {
            $ventaController->delete($id);
        }, 'middleware' => ['onlysupervisor']], // Only supervisors can delete legacy sales
    ],
    '/ventas/detail/{id}' => [
        'GET' => ['handler' => function ($id) use ($ventaController) {
            $ventaController->showDetail($id);
        }, 'middleware' => ['onlysupervisor']], // Only supervisors can view legacy sales
    ],
    '/catalog' => [
        'GET' => ['handler' => function () use ($cartController) {
            $cartController->showCatalog();
        }, 'middleware' => ['login']],
    ],
    '/cart/add' => [
        'POST' => ['handler' => function () use ($cartController) {
            $cartController->addToCart();
        }, 'middleware' => ['login']],
    ],
    '/cart' => [
        'GET' => ['handler' => function () use ($cartController) {
            $cartController->showCart();
        }, 'middleware' => ['login']],
    ],
    '/cart/update' => [
        'POST' => ['handler' => function () use ($cartController) {
            $cartController->updateCartItem();
        }, 'middleware' => ['login']],
    ],
    '/cart/remove/{id}' => [
        'GET' => ['handler' => function ($id) use ($cartController) {
            $cartController->removeFromCart((int)$id);
        }, 'middleware' => ['login']],
    ],
    '/cart/checkout' => [
        'GET' => ['handler' => function () use ($cartController) {
            $cartController->checkout();
        }, 'middleware' => ['login']],
    ],
    '/pedidos' => [
        'GET' => ['handler' => function () use ($pedidoController) {
            $pedidoController->index();
        }, 'middleware' => ['login']],
    ],
    '/pedidos/detail/{id}' => [
        'GET' => ['handler' => function ($id) use ($pedidoController) {
            $pedidoController->showDetail((int)$id);
        }, 'middleware' => ['login']],
    ],
    '/pedidos/edit/{id}' => [
        'GET' => ['handler' => function ($id) use ($pedidoController) {
            $pedidoController->showFormEdit((int)$id);
        }, 'middleware' => ['login']],
    ],
    '/pedidos/update' => [
        'POST' => ['handler' => function () use ($pedidoController) {
            $pedidoController->update();
        }, 'middleware' => ['login']],
    ],
    '/pedidos/delete/{id}' => [
        'GET' => ['handler' => function ($id) use ($pedidoController) {
            $pedidoController->showConfirmDelete((int)$id);
        }, 'middleware' => ['login']],
    ],
    '/pedidos/delete_confirm/{id}' => [
        'POST' => ['handler' => function ($id) use ($pedidoController) {
            $pedidoController->delete((int)$id);
        }, 'middleware' => ['login']],
    ],
];

// Middleware definitions
$middleware = [
    'admin' => function () {
        \App\Controladores\AuthMiddleware::requireAdmin();
    },
    'login' => function () {
        \App\Controladores\AuthMiddleware::requireLogin();
    },
    'supervisor' => function () {
        \App\Controladores\AuthMiddleware::requireSupervisor();
    },
    'onlysupervisor' => function () {
        \App\Controladores\AuthMiddleware::requireOnlySupervisor();
    },
];

// ... existing code ...

$matched = false;
foreach ($routes as $pattern => $methods) {
    // Convert route pattern to regex
    $regex = '#^' . preg_replace('#/{([a-zA-Z0-9_]+)}#', '/(?P<$1>[^/]+)', $pattern) . '$#';
    error_log("Matching route: Pattern='{$pattern}', Regex='{$regex}', Route='{$route}'");
    if (preg_match($regex, $route, $matches)) {
        error_log("Route matched: Pattern='{$pattern}', Matches=" . print_r($matches, true));
        if (isset($methods[$http_method])) {
            // ... execute middleware and handler ...
            $route_config = $methods[$http_method];
            $handler = $route_config['handler'];
            $route_middleware = $route_config['middleware'] ?? [];

            // Execute middleware
            foreach ($route_middleware as $mw_key) {
                if (isset($middleware[$mw_key])) {
                    $middleware[$mw_key]();
                } else {
                    error_log("Middleware '{$mw_key}' not found.");
                    // Handle missing middleware error
                    header("HTTP/1.0 500 Internal Server Error");
                    $smarty->assign('title', '500 Internal Server Error');
                    $smarty->display('404.tpl'); // Or a specific error template
                    exit();
                }
            }

            // Extract named parameters
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            // Call the handler with parameters
            call_user_func_array($handler, $params);
            $matched = true;
            break;
        }
    }
}

if (!$matched) {
    header("HTTP/1.0 404 Not Found");
    $smarty->assign('title', '404 Not Found');
    $smarty->display('404.tpl');
}
