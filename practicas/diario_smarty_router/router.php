<?php

require_once __DIR__ . '/src/News.php';
require_once __DIR__ . '/src/NewsRepository.php';
require_once __DIR__ . '/src/Router.php';

use App\Router;

$router = new Router();
$router->handleRequest();

?>