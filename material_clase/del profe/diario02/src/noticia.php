<?php
  require_once("bd.php");
 
  class Noticia {

    public function show($id) {
      global $db;

      $index = 0;
      while ($db[$index]['id'] != $id) {
        $index ++;
      };
      if ($index < count($db)) {
        $noticia = $db[$index];
      } else {
        die('Noticia no encontrada');
      }
      
      $smarty = new Smarty\Smarty; 
      $smarty->setTemplateDir('./templates')
        ->setCompileDir('./templates_c');
    
      $smarty->assign('noticia', $noticia);
      $smarty->assign('BASE_URL', BASE_URL);
      
      $smarty->display('noticia.html');
    
    }
  }
