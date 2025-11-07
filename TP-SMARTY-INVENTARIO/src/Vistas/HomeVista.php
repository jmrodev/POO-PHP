<?php

class HomeVista extends Vista
{
    public function displayHome()
    {
        $this->smarty->display('home.tpl');
    }
}
