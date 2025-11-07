<?php

require_once 'libs/smarty-5.4.2/libs/Smarty.class.php';
require_once 'src/Database/db_connection.php';

$smarty = new Smarty\Smarty();
$smarty->setTemplateDir('templates');
$smarty->setCompileDir('templates_c');
$smarty->setCacheDir('cache');

define('BASE_URL', rtrim('http://'.$_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['PHP_SELF']), '/') . '/');
define('SERVER_PATH', $_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['PHP_SELF']));

$smarty->assign('BASE_URL', BASE_URL);
$smarty->assign('SERVER_PATH', SERVER_PATH);

$request_uri = $_SERVER['REQUEST_URI'];
$script_name = $_SERVER['SCRIPT_NAME'];
$base_path = str_replace(basename($script_name), '', $script_name);
$route = str_replace($base_path, '', $request_uri);
$route = strtok($route, '?');

if (empty($route)) {
    $route = '/';
}

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

if (array_key_exists($route, $routes)) {
    require_once 'routes/' . $routes[$route];
} else {
    header("HTTP/1.0 404 Not Found");
    $smarty->assign('title', '404 Not Found');
    $smarty->display('404.tpl');
}
