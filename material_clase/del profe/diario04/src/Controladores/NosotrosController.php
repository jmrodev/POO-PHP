<?php
  require_once(SERVER_PATH."/src/Modelos/NosotrosModel.php");
  require_once(SERVER_PATH."/src/Vistas/NosotrosVista.php");

  class NosotrosController {

    private $model, $vista;

    public function __construct()
    {
      $this->model = new NosotrosModel();
      $this->vista = new NosotrosVista();  
    }

    public function show() {

      $nosotros = $this->model->getAll();
      $this->vista->view($nosotros);
  
    }

  }

