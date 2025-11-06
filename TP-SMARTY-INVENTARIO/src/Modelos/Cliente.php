<?php

class Cliente extends Persona
{
    protected string $dni;
    protected array $ventas = [];

    public function __construct(?int $id, string $nombre, string $username, string $password, string $dni)
    {
        parent::__construct($id, $nombre, $username, $password, 'client');
        $this->dni = $dni;
        $this->ventas = [];
    }

    public function getDni(): string
    {
        return $this->dni;
    }

    public function setDni(string $dni): void
    {
        $this->dni = $dni;
    }

    public function getVentas(): array
    {
        return $this->ventas;
    }

    public function addVenta($venta): void
    {
        $this->ventas[] = $venta;
    }
}
