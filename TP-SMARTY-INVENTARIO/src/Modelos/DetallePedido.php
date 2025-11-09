<?php

namespace App\Modelos;

class DetallePedido
{
    private ?int $id;
    private int $pedidoId;
    private int $repuestoId;
    private int $cantidad;
    private float $precioUnitario;
    private ?Repuesto $repuesto; // Optional: to hold the associated repuesto object

    public function __construct(?int $id, int $pedidoId, int $repuestoId, int $cantidad, float $precioUnitario, ?Repuesto $repuesto = null)
    {
        $this->id = $id;
        $this->pedidoId = $pedidoId;
        $this->repuestoId = $repuestoId;
        $this->cantidad = $cantidad;
        $this->precioUnitario = $precioUnitario;
        $this->repuesto = $repuesto;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPedidoId(): int
    {
        return $this->pedidoId;
    }

    public function getRepuestoId(): int
    {
        return $this->repuestoId;
    }

    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    public function getPrecioUnitario(): float
    {
        return $this->precioUnitario;
    }

    public function getRepuesto(): ?Repuesto
    {
        return $this->repuesto;
    }

    // Setters
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setPedidoId(int $pedidoId): void
    {
        $this->pedidoId = $pedidoId;
    }

    public function setRepuestoId(int $repuestoId): void
    {
        $this->repuestoId = $repuestoId;
    }

    public function setCantidad(int $cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    public function setPrecioUnitario(float $precioUnitario): void
    {
        $this->precioUnitario = $precioUnitario;
    }

    public function setRepuesto(?Repuesto $repuesto): void
    {
        $this->repuesto = $repuesto;
    }
}
