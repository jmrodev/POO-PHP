<?php

namespace App\Validators;

use App\Repositories\RepuestoRepository;
use App\Repositories\PersonaRepository;

class VentaValidator
{
    private $errors = [];
    private RepuestoRepository $repuestoRepository;
    private PersonaRepository $personaRepository;

    public function __construct(RepuestoRepository $repuestoRepository, PersonaRepository $personaRepository)
    {
        $this->repuestoRepository = $repuestoRepository;
        $this->personaRepository = $personaRepository;
    }

    public function validate(array $data, bool $isUpdate = false)
    {
        if ($isUpdate && (!isset($data['id']) || empty($data['id']))) {
            $this->errors[] = "ID de la venta es obligatorio para actualizar.";
        }
        if (!isset($data['repuesto_id']) || empty($data['repuesto_id'])) {
            $this->errors[] = "El ID del repuesto es obligatorio.";
        }
        if (!isset($data['usuario_id']) || empty($data['usuario_id'])) {
            $this->errors[] = "El ID del usuario es obligatorio.";
        }
        if (!isset($data['cantidad']) || !is_numeric($data['cantidad']) || $data['cantidad'] <= 0) {
            $this->errors[] = "La cantidad debe ser un número positivo.";
        }

        // Validate repuesto and usuario existence
        $repuesto = $this->repuestoRepository->obtenerPorId($data['repuesto_id']);
        if (!$repuesto) {
            $this->errors[] = "Repuesto no válido.";
        }

        if (isset($data['usuario_id']) && !empty($data['usuario_id'])) {
            $usuario = $this->personaRepository->findById($data['usuario_id']); // Changed from clienteRepository->obtenerPorId
            if (!$usuario) {
                $this->errors[] = "Usuario no válido.";
            }
        }

        // Stock validation (only for create and if repuesto is valid)
        if (!$isUpdate && isset($data['repuesto_id']) && !empty($data['repuesto_id'])) {
            $repuesto = $this->repuestoRepository->obtenerPorId($data['repuesto_id']);
            if ($repuesto) {
                $currentStock = $repuesto->getCantidad();
                if ($currentStock < $data['cantidad']) {
                    $this->errors[] = "No hay suficiente stock para el repuesto seleccionado. Stock actual: " . $currentStock;
                }
            }
        }

        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}