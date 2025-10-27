<?php
    define('BASE_URL', '//'.$_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['PHP_SELF']).'/');
    // Option 1: Define SERVER_PATH based on the web server's document root and script's directory.
    // This is useful when the project is deployed directly under the web server's document root
    // or a virtual host, and the path needs to be relative to that web-accessible root.
    // However, it can be problematic if the web server's document root doesn't match the
    // actual file system path where the project resides, or in certain server configurations.
    // define('SERVER_PATH', $_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['PHP_SELF']));

    // Option 2: Define SERVER_PATH based on the absolute file system path of the current file (__DIR__).
    // This is generally more robust as it directly points to the project's root directory
    // on the file system, regardless of how the web server is configured or where the
    // document root points. It's recommended for consistency across different deployment environments.
    define('SERVER_PATH', __DIR__);
    
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