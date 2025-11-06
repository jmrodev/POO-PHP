<?php

class Administrador extends Persona
{
    public function __construct(?int $id, string $nombre, string $username, string $password)
    {
        parent::__construct($id, $nombre, $username, $password, 'admin');
    }
}
