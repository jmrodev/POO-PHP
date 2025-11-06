<?php

class RepuestoValidator {
    private $errors = [];

    public function validate(array $data, array $files, bool $isUpdate = false) {
        if ($isUpdate && (!isset($data['id']) || empty($data['id']))) {
            $this->errors[] = "ID del repuesto es obligatorio para actualizar.";
        }
        if (!isset($data['nombre']) || empty($data['nombre'])) {
            $this->errors[] = "El nombre es obligatorio.";
        }
        if (!isset($data['precio']) || empty($data['precio'])) {
            $this->errors[] = "El precio es obligatorio.";
        } elseif (!is_numeric($data['precio']) || $data['precio'] <= 0) {
            $this->errors[] = "El precio debe ser un número positivo.";
        }
        if (!isset($data['cantidad']) || empty($data['cantidad'])) {
            $this->errors[] = "La cantidad es obligatoria.";
        } elseif (!is_numeric($data['cantidad']) || $data['cantidad'] < 0) {
            $this->errors[] = "La cantidad debe ser un número no negativo.";
        }

        // Image validation (only if a new image is uploaded)
        if (isset($files['imagen']) && $files['imagen']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF];
            $detectedType = exif_imagetype($files['imagen']['tmp_name']);

            if (!in_array($detectedType, $allowedTypes)) {
                $this->errors[] = "El archivo de imagen debe ser JPG, PNG o GIF.";
            }
            // You can add more checks here, e.g., file size
        }

        return empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }
}

?>
