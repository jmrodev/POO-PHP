<?php

namespace App;

require_once __DIR__ . '/../libs/libs/Smarty.class.php';

class Router
{
    private $smarty;
    private $newsRepository;
    private $baseUrl = '/test_jmro/POO/diario_smarty_router_seo_friendly/'; // Define your base URL here

    public function __construct()
    {
        $this->smarty = new \Smarty\Smarty();
        $this->smarty->setTemplateDir(__DIR__ . '/../templates/');
        $this->smarty->setCompileDir(__DIR__ . '/../templates_c/');
        $this->smarty->setCacheDir(__DIR__ . '/../cache/');

        $this->newsRepository = new NewsRepository();
        $this->smarty->assign('base_url', $this->baseUrl);
    }

    public function handleRequest()
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestUri = str_replace($this->baseUrl, '', $requestUri);
        $segments = explode('/', trim($requestUri, '/'));

        $action = array_shift($segments);
        $id = null;

        if (isset($segments[0])) {
            $id = (int)$segments[0];
        }

        switch ($action) {
            case '':
            case 'index':
                $noticias = $this->newsRepository->getAllNews();
                $this->smarty->assign('noticias', $noticias);
                $this->smarty->display('index.tpl');
                break;
            case 'noticia':
                if ($id) {
                    $noticia_encontrada = $this->newsRepository->getNewsById($id);

                    if ($noticia_encontrada) {
                        $this->smarty->assign('noticia', $noticia_encontrada);
                        $this->smarty->display('noticia.tpl');
                    } else {
                        header("HTTP/1.0 404 Not Found");
                        echo "Noticia no encontrada";
                    }
                } else {
                    header("HTTP/1.0 404 Not Found");
                    echo "ID de noticia no especificado";
                }
                break;
            default:
                header("HTTP/1.0 404 Not Found");
                echo "Página no encontrada";
                break;
        }
    }
}
