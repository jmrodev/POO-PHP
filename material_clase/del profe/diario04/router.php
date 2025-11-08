<?php
    define('BASE_URL', '//'.$_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['PHP_SELF']).'/');
    define('SERVER_PATH', $_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['PHP_SELF']));
    
    require_once("src/Controladores/IndexController.php");
    require_once("src/Controladores/NosotrosController.php");
    require_once("src/Controladores/NoticiasController.php");
    
    if (array_key_exists('action', $_GET)) {
        $action = $_GET['action'];        
    } else {
        $action = 'index';    
    }
    
    $parametros = explode('/', $action);

    switch ($parametros[0]) {
        case 'nosotros': {
            $nosotros = new NosotrosController();
            $nosotros->show();
        }; break;
        case 'index': {
            $index = new IndexController();
            $index->show();
        }; break;
        case 'noticia': {
            $id = $parametros[1];
            $noticia = new NoticiasController();
            $noticia->show($id);
        }; break;
        default: {
            $index = new IndexController();
            $index->show();
        }
    }