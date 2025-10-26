<?php
  require_once("libs/smarty-5.4.2/libs/Smarty.class.php");
  require_once("bd.php");

  class Index {

     function show() {
          global $db;
          
          $smarty = new Smarty\Smarty; 
          $smarty->setTemplateDir('./templates')
               ->setCompileDir('./templates_c');
        
          $smarty->assign('noticias', $db);
          $smarty->assign('BASE_URL', BASE_URL);
          
        
          $smarty->display('index.html');
        
     }
  }

