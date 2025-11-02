<?php
require_once(SERVER_PATH."/src/Vistas/Vista.php");

class HomeVista extends Vista {
    public function displayHome() {
        $this->smarty->display('home.tpl');
    }
}
?>