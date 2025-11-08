<?php
    define('BASE_URL', '//'.$_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['PHP_SELF']).'/');

    require_once("src/index.php");
    require_once("src/nosotros.php");
    require_once("src/noticia.php");
    
    if (array_key_exists('action', $_GET)) {
        $action = $_GET['action'];        
    } else {
        $action = 'index';    
    }
    
    $parametros = explode('/', $action);

    switch ($parametros[0]) {
        case 'nosotros': {
            $nosotros = new Nosotros();
            $nosotros->show();
        }; break;
        case 'index': {
            $index = new Index();
            $index->show();
        }; break;
        case 'noticia': {
            $id = $parametros[1];
            $noticia = new Noticia();
            $noticia->show($id);
        }; break;
        default: {
            $index = new Index();
            $index->show();
        }
    }