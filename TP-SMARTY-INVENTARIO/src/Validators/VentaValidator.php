<?php

class VentaValidator {
    private $errors = [];
    private $repuestoRepository;
    private $clienteRepository;

    public function __construct(RepuestoRepository $repuestoRepository, ClienteRepository $clienteRepository) {
        $this->repuestoRepository = $repuestoRepository;
        $this->clienteRepository = $clienteRepository;
    }

    public function validate(array $data, bool $isUpdate = false) {
        if ($isUpdate && (!isset($data['id']) || empty($data['id']))) {
            $this->errors[] = "ID de la venta es obligatorio para actualizar.";
        }
        if (!isset($data['repuesto_id']) || empty($data['repuesto_id'])) {
            $this->errors[] = "El ID del repuesto es obligatorio.";
        }
        if (!isset($data['cliente_id']) || empty($data['cliente_id'])) {
            $this->errors[] = "El ID del cliente es obligatorio.";
        }
        if (!isset($data['cantidad']) || empty($data['cantidad'])) {
            $this->errors[] = "La cantidad es obligatoria.";
        } elseif (!is_numeric($data['cantidad']) || $data['cantidad'] <= 0) {
            $this->errors[] = "La cantidad debe ser un número positivo.";
        }

        // Validate repuesto and cliente existence
        if (isset($data['repuesto_id']) && !empty($data['repuesto_id'])) {
            $repuesto = $this->repuestoRepository->obtenerPorId($data['repuesto_id']);
            if (!$repuesto) {
                $this->errors[] = "Repuesto no válido.";
            }
        }

        if (isset($data['cliente_id']) && !empty($data['cliente_id'])) {
            $cliente = $this->clienteRepository->obtenerPorId($data['cliente_id']);
            if (!$cliente) {
                $this->errors[] = "Cliente no válido.";
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

    public function getErrors() {
        return $this->errors;
    }
}

?>
