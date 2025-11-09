<?php

namespace App\Modelos;

class Pedido
{
    private ?int $id;
    private int $usuarioId;
    private string $fechaPedido;
    private float $total;
    private string $estado;
    private ?Usuario $usuario; // Optional: to hold the associated user object
    private array $detalles; // To hold an array of DetallePedido objects

    public function __construct(?int $id, int $usuarioId, string $fechaPedido, float $total, string $estado = 'pendiente', ?Usuario $usuario = null, array $detalles = [])
    {
        $this->id = $id;
        $this->usuarioId = $usuarioId;
        $this->fechaPedido = $fechaPedido;
        $this->total = $total;
        $this->estado = $estado;
        $this->usuario = $usuario;
        $this->detalles = $detalles;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuarioId(): int
    {
        return $this->usuarioId;
    }

    public function getFechaPedido(): string
    {
        return $this->fechaPedido;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function getDetalles(): array
    {
        return $this->detalles;
    }

    // Setters
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setUsuarioId(int $usuarioId): void
    {
        $this->usuarioId = $usuarioId;
    }

    public function setFechaPedido(string $fechaPedido): void
    {
        $this->fechaPedido = $fechaPedido;
    }

    public function setTotal(float $total): void
    {
        $this->total = $total;
    }

    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    public function setUsuario(?Usuario $usuario): void
    {
        $this->usuario = $usuario;
    }

    public function setDetalles(array $detalles): void
    {
        $this->detalles = $detalles;
    }

    public function addDetalle(DetallePedido $detalle): void
    {
        $this->detalles[] = $detalle;
    }
}
