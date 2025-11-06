<?php
require_once 'libs/smarty-5.4.2/libs/Smarty.class.php';
require_once 'src/Database/db_connection.php';

$smarty = new Smarty();
$smarty->setTemplateDir('templates');
$smarty->setCompileDir('templates_c');
$smarty->setCacheDir('cache');

// Definir constantes
define('BASE_URL', '//'.$_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['PHP_SELF']).'/');
define('SERVER_PATH', $_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['PHP_SELF']));

// Asignar constantes a Smarty
$smarty->assign('BASE_URL', BASE_URL);
$smarty->assign('SERVER_PATH', SERVER_PATH);

// Obtener la ruta de la URL
$request_uri = $_SERVER['REQUEST_URI'];
$script_name = $_SERVER['SCRIPT_NAME'];
$base_path = str_replace(basename($script_name), '', $script_name);
$route = str_replace($base_path, '', $request_uri);
$route = strtok($route, '?'); // Eliminar parámetros de consulta

// Definir rutas
$routes = [
    '/' => 'home.php',
    '/home' => 'home.php',
    '/login' => 'login.php',
    '/register' => 'register.php',
    '/clientes' => 'clientes.php',
    '/clientes/add' => 'clientes.php',
    '/clientes/edit' => 'clientes.php',
    '/clientes/delete' => 'clientes.php',
    '/repuestos' => 'repuestos.php',
    '/repuestos/add' => 'repuestos.php',
    '/repuestos/edit' => 'repuestos.php',
    '/repuestos/delete' => 'repuestos.php',
    '/ventas' => 'ventas.php',
    '/ventas/add' => 'ventas.php',
    '/ventas/edit' => 'ventas.php',
    '/ventas/delete' => 'ventas.php',
];

// Enrutamiento
if (array_key_exists($route, $routes)) {
    require_once 'routes/' . $routes[$route];
} else {
    // Manejar 404
    header("HTTP/1.0 404 Not Found");
    $smarty->assign('title', '404 Not Found');
    $smarty->display('404.tpl'); // Asume que tienes una plantilla 404.tpl
}
?>