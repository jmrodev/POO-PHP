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

        // Dynamically determine the base URL for SEO-friendly routing
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $this->baseUrl = rtrim(str_replace(basename($scriptName), '', $scriptName), '/\\') . '/';
        $this->smarty->assign('base_url', $this->baseUrl);

        error_log("Router: Base URL calculated as: " . $this->baseUrl);
    }

    public function handleRequest()
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        error_log("Router: Raw Request URI: " . $requestUri);
        error_log("Router: Base URL for stripping: " . $this->baseUrl);

        // Remove the base URL from the request URI to get the clean path for routing
        $requestUri = substr($requestUri, strlen($this->baseUrl));
        error_log("Router: Cleaned Request URI: " . $requestUri);

        $segments = explode('/', trim($requestUri, '/'));

        $action = array_shift($segments);
        $id = null;

        if (isset($segments[0])) {
            $id = (int)$segments[0];
        }

        error_log("Router: Action: " . ($action ?: '' ) . ", ID: " . ($id ?: '' ));

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
