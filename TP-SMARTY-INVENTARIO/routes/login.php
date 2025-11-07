<?php

// routes/login.php

require_once SERVER_PATH . '/src/Controladores/LoginController.php';

$loginController = new LoginController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginController->processLogin();
} else {
    $loginController->showLoginForm();
}
