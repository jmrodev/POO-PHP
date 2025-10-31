<?php

namespace App;

require_once __DIR__ . '/../libs/libs/Smarty.class.php';

class Router
{
    private $smarty;
    private $newsRepository;
    private $baseUrl;

    public function __construct()
    {
        $this->smarty = new \Smarty\Smarty();
        $this->smarty->setTemplateDir(__DIR__ . '/../templates/');
        $this->smarty->setCompileDir(__DIR__ . '/../templates_c/');
        $this->smarty->setCacheDir(__DIR__ . '/../cache/');

        $this->newsRepository = new NewsRepository();
        $this->baseUrl = rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . '/';
        $this->smarty->assign('base_url', $this->baseUrl);
    }

    public function handleRequest()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case '':
            case 'index':
                $noticias = $this->newsRepository->getAllNews();
                $this->smarty->assign('noticias', $noticias);
                $this->smarty->display('index.tpl');
                break;
            case 'noticia':
                $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                $noticia_encontrada = $this->newsRepository->getNewsById($id);

                if ($noticia_encontrada) {
                    $this->smarty->assign('noticia', $noticia_encontrada);
                    $this->smarty->display('noticia.tpl');
                } else {
                    header("HTTP/1.0 404 Not Found");
                    echo "Noticia no encontrada";
                }
                break;
            default:
                header("HTTP/1.0 404 Not Found");
                echo "Página no encontrada";
                break;
        }
    }
}
