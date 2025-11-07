<?php

namespace App\Vistas;

abstract class BaseView
{
    protected $smarty;

    public function __construct()
    {
        global $smarty; // Access the global Smarty instance
        $this->smarty = $smarty;
    }

    protected function display($template, $data = [])
    {
        foreach ($data as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->display($template);
    }

    // Common methods for assigning data or displaying specific parts of the view can be added here
}
