<?php
    require_once("libs/smarty-5.4.2/libs/Smarty.class.php");

    $smarty = new Smarty\Smarty; 
    $smarty->setTemplateDir(__DIR__ . '/templates')
      ->setCompileDir(__DIR__ . '/templates_c');

    $nosotros = ['Editor: Juan Garay', 'Fotografia: Steve Gomez'];

    $smarty->assign('nosotros', $nosotros);

    $smarty->display('nosotros.html');

  