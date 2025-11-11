<?php

namespace App\Controladores;

use Smarty;
use App\Database\DBConnection;
use App\Services\AuthService; // Add this use statement

abstract class BaseController
{
    protected \Smarty $smarty;
    protected \PDO $db;
    protected AuthService $authService; // Add this property

    public function __construct(\Smarty $smarty, AuthService $authService) // Add AuthService to constructor
    {
        $this->smarty = $smarty;
        $this->db = DBConnection::getInstance()->getConnection();
        $this->authService = $authService; // Assign the service
        $this->smarty->assign('authService', $this->authService); // Assign to Smarty
    }

    protected function redirect($url)
    {
        header('Location: ' . $url);
        exit();
    }
}
