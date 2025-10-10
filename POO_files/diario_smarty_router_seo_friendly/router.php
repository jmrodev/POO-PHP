<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/src/News.php';
require_once __DIR__ . '/src/NewsRepository.php';
require_once __DIR__ . '/src/Router.php';

use App\Router;

$router = new Router();
$router->handleRequest();

?>