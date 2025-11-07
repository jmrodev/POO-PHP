<?php

// routes/home.php

require_once SERVER_PATH . '/src/Controladores/BaseController.php';
require_once SERVER_PATH . '/src/Controladores/HomeController.php';

$homeController = new HomeController();
$homeController->show();
