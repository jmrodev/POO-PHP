<?php

class Repuesto {

    protected $id;
    protected $nombre;
    protected $precio;
    protected $cantidad;
    protected $imagen;

    public function __construct($id = null, $nombre = null, $precio = null, $cantidad = null, $imagen = null) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->cantidad = $cantidad;
        $this->imagen = $imagen;
    }

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function getCantidad() {
        return $this->cantidad;
    }

    public function getImagen() {
        return $this->imagen;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    public function setImagen($imagen) {
        $this->imagen = $imagen;
    }
}
