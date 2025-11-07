<?php

// routes/register.php

require_once SERVER_PATH . '/src/Controladores/RegisterController.php';

$registerController = new RegisterController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registerController->processRegister();
} else {
    $registerController->showRegisterForm();
}
