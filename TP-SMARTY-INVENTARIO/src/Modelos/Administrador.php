<?php

namespace App\Modelos; // Add this line

use App\Modelos\Persona; // Add this line

class Administrador extends Persona
{
    public function __construct(?int $id, string $nombre, string $username, string $password)
    {
        parent::__construct($id, $nombre, $username, $password, 'admin');
    }
}
