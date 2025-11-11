<?php

// routes/home.php
$router->get('/', function () use ($homeController) {
    $homeController->showHome();
});

$router->get('/home', function () use ($homeController) {
    $homeController->showHome();
});