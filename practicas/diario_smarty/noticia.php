<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'datos.php';
require_once 'libs/libs/Smarty.class.php';

$smarty = new Smarty\Smarty();

$smarty->setTemplateDir(__DIR__ . '/templates');
$smarty->setCompileDir(__DIR__ . '/templates_c');
$smarty->setCacheDir(__DIR__ . '/cache');

$id_noticia = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$noticia_encontrada = null; 

foreach ($noticias as $noticia) {
    if ($noticia['id'] === $id_noticia) {
        $noticia_encontrada = $noticia;
        break;
    }
}

$smarty->assign('noticia_encontrada', $noticia_encontrada);

$smarty->display('noticia.tpl');
?>
