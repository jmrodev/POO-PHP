<?php

class Venta {
    private $id;
    private $repuesto;
    private $cliente;
    private $cantidad;
    private $fecha;

    public function __construct(?int $id = null, ?Repuesto $repuesto = null, ?Cliente $cliente = null, ?int $cantidad = null, ?string $fecha = null) {
        $this->id = $id;
        $this->repuesto = $repuesto;
        $this->cliente = $cliente;
        $this->cantidad = $cantidad;
        $this->fecha = $fecha;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getRepuesto(): Repuesto {
        return $this->repuesto;
    }

    public function getCliente(): Cliente {
        return $this->cliente;
    }

    public function getCantidad(): int {
        return $this->cantidad;
    }

    public function getFecha(): string {
        return $this->fecha;
    }
}
