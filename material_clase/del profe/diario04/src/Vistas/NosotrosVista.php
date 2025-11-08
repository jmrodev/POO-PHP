<?php

    class NosotrosVista extends Vista {

        function view($nosotros) {

            $this->smarty->assign('nosotros', $nosotros);
            $this->smarty->display('nosotros.html');
      

        }

    }