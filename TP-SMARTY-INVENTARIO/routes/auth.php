<?php

// routes/auth.php
$router->get('/login', function () use ($loginController) {
    $loginController->showLoginForm();
});
$router->post('/login', function () use ($loginController) {
    $loginController->login();
});

$router->get('/logout', function () use ($loginController) {
    $loginController->logout();
});

$router->get('/register', function () use ($registerController) {
    $registerController->showRegisterForm();
});
$router->post('/register', function () use ($registerController) {
    $registerController->register();
});
