<?php

require_once SERVER_PATH . '/src/Vistas/BaseView.php';

class RegisterVista extends BaseView
{
    public function __construct()
    {
        parent::__construct();
    }

    public function displayRegister($data = [])
    {
        $this->display('register.tpl', $data);
    }
}
