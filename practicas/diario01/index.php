<?php
  require_once("libs/smarty-5.4.2/libs/Smarty.class.php");
  require_once("bd.php");

  include_once("templates/header.html");


  $smarty = new Smarty\Smarty; 
  $smarty->setTemplateDir(__DIR__ . '/templates')
       ->setCompileDir(__DIR__ . '/templates_c');

  $smarty->assign('noticias', $db);

  $smarty->display('index.html');

