<?php

    class NoticiasVista extends Vista{

        function view($noticia) {

            $this->smarty->assign('noticia', $noticia);
            $this->smarty->display('noticia.html');

        }

    }