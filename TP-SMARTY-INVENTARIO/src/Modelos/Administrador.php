<?php

namespace App\Modelos;

use App\Modelos\Persona;

class Administrador extends Persona
{
    protected ?string $dni;

    public function __construct(?int $id, string $nombre, string $username, string $password, ?string $dni = null)
    {
        parent::__construct($id, $nombre, $username, $password, 'admin');
        $this->dni = $dni;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(?string $dni): void
    {
        $this->dni = $dni;
    }
}
