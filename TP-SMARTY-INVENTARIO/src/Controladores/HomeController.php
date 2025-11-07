<?php

class HomeController extends BaseController
{
    private $homeVista;

    public function __construct()
    {
        parent::__construct();
        $this->homeVista = $this->loadView('HomeVista');
    }

    public function show()
    {
        $this->homeVista->displayHome();
    }
}
