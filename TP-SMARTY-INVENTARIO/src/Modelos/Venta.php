<?php

namespace App\Modelos;

use App\Modelos\Repuesto;
use App\Modelos\Usuario;

class Venta
{
    private $id;
    private $repuesto;
    private $usuario;
    private $cantidad;
    private $fecha;

    public function __construct(?int $id = null, ?Repuesto $repuesto = null, ?Usuario $usuario = null, ?int $cantidad = null, ?string $fecha = null)
    {
        $this->id = $id;
        $this->repuesto = $repuesto;
        $this->usuario = $usuario;
        $this->cantidad = $cantidad;
        $this->fecha = $fecha;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getRepuesto(): ?Repuesto
    {
        return $this->repuesto;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function getFecha(): ?string
    {
        return $this->fecha;
    }
}
