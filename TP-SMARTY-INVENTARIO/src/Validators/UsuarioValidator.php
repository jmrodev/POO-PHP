<?php

namespace App\Validators;

class UsuarioValidator
{
    private $errors = [];

    public function validate(array $data, bool $isUpdate = false)
    {
        if ($isUpdate && (!isset($data['id']) || empty($data['id']))) {
            $this->errors[] = "ID del usuario es obligatorio para actualizar.";
        }
        if (!isset($data['nombre']) || empty($data['nombre'])) {
            $this->errors[] = "El nombre es obligatorio.";
        }
        if (!isset($data['dni']) || empty($data['dni'])) {
            $this->errors[] = "El DNI es obligatorio.";
        } elseif (!preg_match('/^\d{8}$/', $data['dni'])) { // Assuming 8 numeric digits for DNI
            $this->errors[] = "El DNI debe contener 8 dígitos numéricos.";
        }
        // Add more validation rules as needed

        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
