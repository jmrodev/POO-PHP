<?php
    require_once("libs/smarty-5.4.2/libs/Smarty.class.php");

  class Nosotros {
    public function show() {
      $smarty = new Smarty\Smarty; 
      $smarty->setTemplateDir('./templates')
        ->setCompileDir('./templates_c');
  
      $nosotros = ['Editor: Juan Garay', 'Fotografia: Steve Gomez'];
  
      $smarty->assign('nosotros', $nosotros);
      $smarty->assign('BASE_URL', BASE_URL);
        
      $smarty->display('nosotros.html');
  
    }
  }

