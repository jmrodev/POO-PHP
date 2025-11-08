<?php
  require_once(SERVER_PATH."/src/Modelos/NoticiasModel.php");
  require_once(SERVER_PATH."/src/Vistas/IndexVista.php");

  class IndexController {

    private $model, $vista;

    public function __construct()
    {
      $this->model = new NoticiasModel();
      $this->vista = new IndexVista();  
    }

     function show() {
          
          $noticias = $this->model->getAll();
          $this->vista->view($noticias);
     }
  }

