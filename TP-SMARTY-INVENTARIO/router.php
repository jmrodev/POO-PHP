<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    define('BASE_URL', '//'.$_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['PHP_SELF']).'/');
    define('SERVER_PATH', __DIR__);
    
    require_once("src/Controladores/ClienteController.php");
    require_once("src/Controladores/RepuestoController.php");
    require_once("src/Controladores/VentaController.php");
    require_once("src/Controladores/HomeController.php");
    require_once("src/Controladores/LoginController.php");
    require_once("src/Controladores/AuthMiddleware.php");
    
    if (array_key_exists('action', $_GET)) {
        $action = $_GET['action'];        
    } else {
        $action = 'login';    
    }
    
    $parametros = explode('/', $action);

    switch ($parametros[0]) {
        case 'login': {
            $loginController = new LoginController();
            $loginController->showLoginForm();
        }; break;
        case 'authenticate': {
            $loginController = new LoginController();
            $loginController->authenticate();
        }; break;
        case 'logout': {
            $loginController = new LoginController();
            $loginController->logout();
        }; break;
        case 'home': {
            AuthMiddleware::requireLogin();
            $homeController = new HomeController();
            $homeController->show();
        }; break;
        case 'clientes': {
            AuthMiddleware::requireLogin();
            if (isset($parametros[1])) {
                switch ($parametros[1]) {
                    case 'create':
                        $clienteController = new ClienteController();
                        $clienteController->showFormCreate();
                        break;
                    case 'store':
                        $clienteController = new ClienteController();
                        $clienteController->create();
                        break;
                    case 'edit':
                        $clienteController = new ClienteController();
                        $clienteController->showFormEdit($parametros[2]);
                        break;
                    case 'update':
                        $clienteController = new ClienteController();
                        $clienteController->update();
                        break;
                    case 'delete':
                        $clienteController = new ClienteController();
                        $clienteController->delete($parametros[2]);
                        break;
                    case 'confirmdelete':
                        $clienteController = new ClienteController();
                        $clienteController->showConfirmDelete($parametros[2]);
                        break;
                    default:
                        $clienteController = new ClienteController();
                        $clienteController->showAll();
                        break;
                }
            } else {
                $clienteController = new ClienteController();
                $clienteController->showAll();
            }
        }; break;
        case 'repuestos': {
            AuthMiddleware::requireLogin();
            if (isset($parametros[1])) {
                switch ($parametros[1]) {
                    case 'create':
                        $repuestoController = new RepuestoController();
                        $repuestoController->showFormCreate();
                        break;
                    case 'store':
                        $repuestoController = new RepuestoController();
                        $repuestoController->create();
                        break;
                    case 'edit':
                        $repuestoController = new RepuestoController();
                        $repuestoController->showFormEdit($parametros[2]);
                        break;
                    case 'update':
                        $repuestoController = new RepuestoController();
                        $repuestoController->update();
                        break;
                    case 'delete':
                        $repuestoController = new RepuestoController();
                        $repuestoController->delete($parametros[2]);
                        break;
                    case 'confirmdelete':
                        $repuestoController = new RepuestoController();
                        $repuestoController->showConfirmDelete($parametros[2]);
                        break;
                    case 'detail':
                        $repuestoController = new RepuestoController();
                        $repuestoController->showDetail($parametros[2]);
                        break;
                    default:
                        $repuestoController = new RepuestoController();
                        $repuestoController->showAll();
                        break;
                }
            } else {
                $repuestoController = new RepuestoController();
                $repuestoController->showAll();
            }
        }; break;
case 'ventas': {
  AuthMiddleware::requireLogin();
            if (isset($parametros[1])) {
                switch ($parametros[1]) {
                case 'create':
                        $ventaController = new VentaController();
                        $ventaController->showFormCreate();
                        break;
                case 'store':
                        $ventaController = new VentaController();
                        $ventaController->create();
                        break;
                    case 'edit':
                        $ventaController = new VentaController();
                        $ventaController->showFormEdit($parametros[2]);
                        break;
                    case 'update':
                        $ventaController = new VentaController();
                        $ventaController->update();
                        break;
                    case 'delete':
                        $ventaController = new VentaController();
                        $ventaController->delete($parametros[2]);
                        break;
                    case 'confirmdelete':
                        $ventaController = new VentaController();
                        $ventaController->showConfirmDelete($parametros[2]);
                        break;
                    default:
                        $ventaController = new VentaController();
                        $ventaController->showAll();
                        break;
                }
            } else {
                $ventaController = new VentaController();
                $ventaController->showAll();
            }
        }; break;
        // Placeholder for other routes (clients, repuestos, ventas)
        default: {
            // Default to home
            // $homeController = new HomeController();
            // $homeController->show();
        }
    }
?>
