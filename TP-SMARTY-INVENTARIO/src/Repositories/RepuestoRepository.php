<?php

class RepuestoRepository
{
    private $db;

    public function __construct(\PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function obtenerTodos()
    {
        $stmt = $this->db->query("SELECT id, nombre, precio, cantidad, imagen FROM repuestos");
        $repuestosData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $repuestos = [];
        foreach ($repuestosData as $data) {
            $repuestos[] = new Repuesto($data['id'], $data['nombre'], $data['precio'], $data['cantidad'], $data['imagen']);
        }
        return $repuestos;
    }

    public function obtenerPorId($id)
    {
        $stmt = $this->db->prepare("SELECT id, nombre, precio, cantidad, imagen FROM repuestos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return new Repuesto($data['id'], $data['nombre'], $data['precio'], $data['cantidad'], $data['imagen']);
        }
        return null;
    }

    public function guardar(Repuesto $repuesto)
    {
        if ($repuesto->getId() === null) {
            $stmt = $this->db->prepare("INSERT INTO repuestos (nombre, precio, cantidad, imagen) VALUES (:nombre, :precio, :cantidad, :imagen)");
            $stmt->bindParam(':nombre', $repuesto->getNombre());
            $stmt->bindParam(':precio', $repuesto->getPrecio());
            $stmt->bindParam(':cantidad', $repuesto->getCantidad(), PDO::PARAM_INT);
            $stmt->bindParam(':imagen', $repuesto->getImagen());
            $result = $stmt->execute();
            if ($result) {
                $repuesto->setId($this->db->lastInsertId());
            }
            return $result;
        } else {
            $stmt = $this->db->prepare("UPDATE repuestos SET nombre = :nombre, precio = :precio, cantidad = :cantidad, imagen = :imagen WHERE id = :id");
            $stmt->bindParam(':nombre', $repuesto->getNombre());
            $stmt->bindParam(':precio', $repuesto->getPrecio());
            $stmt->bindParam(':cantidad', $repuesto->getCantidad(), PDO::PARAM_INT);
            $stmt->bindParam(':imagen', $repuesto->getImagen());
            $stmt->bindParam(':id', $repuesto->getId(), PDO::PARAM_INT);
            return $stmt->execute();
        }
    }

    public function eliminar($id)
    {
        $stmt = $this->db->prepare("DELETE FROM repuestos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function restarCantidad($id, $cantidad)
    {
        $stmt = $this->db->prepare("UPDATE repuestos SET cantidad = cantidad - :cantidad WHERE id = :id AND cantidad >= :cantidad");
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerCantidad($id)
    {
        $stmt = $this->db->prepare("SELECT cantidad FROM repuestos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['cantidad'] : 0;
    }
}
