<?php

require_once("libs/smarty-5.4.2/libs/Smarty.class.php");

$smarty = new Smarty\Smarty; 
$smarty->setTemplateDir('./templates')
     ->setCompileDir('./templates_c');


$secciones = ['Inicio', 'Acerca', 'Contacto'];


$smarty->assign('titulo', 'Hola amigos');
$smarty->assign('secciones', $secciones);


$smarty->display("prueba.tpl");