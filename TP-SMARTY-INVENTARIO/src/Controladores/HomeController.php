<?php

class HomeController {
    private $homeVista;

    public function __construct() {
        $this->homeVista = new HomeVista();
    }

    public function show() {
        $this->homeVista->displayHome();
    }
}
?>