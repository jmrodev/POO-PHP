<?php

namespace App;

class News
{
    public $id;
    public $titulo;
    public $contenido;
    public $fecha;
    public $imagen;

    public function __construct($id, $titulo, $contenido, $fecha, $imagen)
    {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->contenido = $contenido;
        $this->fecha = $fecha;
        $this->imagen = $imagen;
    }
}
