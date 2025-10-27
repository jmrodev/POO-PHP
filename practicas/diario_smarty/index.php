<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'datos.php';
require_once 'libs/libs/Smarty.class.php';

$smarty = new Smarty\Smarty();

$smarty->setTemplateDir('./templates');
$smarty->setCompileDir('./templates_c');
$smarty->setCacheDir('./cache');

$smarty->assign('noticias', $noticias);

$smarty->display('index.tpl');
?>
