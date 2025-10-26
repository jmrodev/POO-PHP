<?php
  require_once(SERVER_PATH."/src/Modelos/NoticiasModel.php");
  require_once(SERVER_PATH."/src/Vistas/NoticiasVista.php");


  class NoticiasController {

    private $model, $vista;

    public function __construct()
    {
      $this->model = new NoticiasModel();
      $this->vista = new NoticiasVista();  
    }

    public function show($id) {

      $noticia = $this->model->get($id);
      if ($noticia == null) {
        $this->vista->error('Noticia no encontrada');
      }

      $this->vista->view($noticia);
    
    }

  }

