<?php
    require_once(SERVER_PATH."/src/Vistas/Vista.php");


    class IndexVista extends Vista {

        function view($noticias) {

            $this->smarty->assign('noticias', $noticias);
            $this->smarty->display('index.html');

        }

    }