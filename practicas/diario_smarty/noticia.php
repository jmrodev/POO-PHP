<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'datos.php';
require_once 'libs/libs/Smarty.class.php';

$smarty = new Smarty\Smarty();

$smarty->setTemplateDir('/home/jmro/Documentos/TECDA/SEGUNDO/POO/POO-PHP/practicas/diario_smarty/templates');
$smarty->setCompileDir('/home/jmro/Documentos/TECDA/SEGUNDO/POO/POO-PHP/practicas/diario_smarty/templates_c');
$smarty->setCacheDir('/home/jmro/Documentos/TECDA/SEGUNDO/POO/POO-PHP/practicas/diario_smarty/cache');

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
