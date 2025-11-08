<?php

namespace App\Controladores;

use Smarty;
use App\Database\DBConnection;

abstract class BaseController
{
    protected \Smarty $smarty;
    protected \PDO $db;

    public function __construct(\Smarty $smarty)
    {
        $this->smarty = $smarty;
        $this->db = DBConnection::getInstance()->getConnection();
    }

    protected function redirect($url)
    {
        header('Location: ' . $url);
        exit();
    }
}
