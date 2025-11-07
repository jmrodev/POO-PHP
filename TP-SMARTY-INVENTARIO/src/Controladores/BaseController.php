<?php

require_once SERVER_PATH . '/src/Database/db_connection.php';
require_once SERVER_PATH . '/src/Vistas/Vista.php';

abstract class BaseController
{
    protected $smarty;
    protected $db;

    public function __construct()
    {
        global $smarty; // Access the global Smarty instance
        $this->smarty = $smarty;
        $this->db = DBConnection::getInstance()->getConnection();
    }

    // Common methods can be added here

    protected function redirect($url)
    {
        header('Location: ' . $url);
        exit();
    }

    protected function loadView($viewClassName)
    {
        require_once SERVER_PATH . '/src/Vistas/' . $viewClassName . '.php';
        return new $viewClassName($this->smarty);
    }
}
