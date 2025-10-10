<?php

namespace App;

class News
{
    public $id;
    public $titulo;
    public $contenido;
    public $fecha;

    public function __construct($id, $titulo, $contenido, $fecha)
    {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->contenido = $contenido;
        $this->fecha = $fecha;
    }
}
