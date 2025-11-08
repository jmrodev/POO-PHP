<?php

namespace App\Repositories;

use App\Modelos\Venta;
use App\Modelos\Repuesto;
use App\Modelos\Usuario;
use PDO;
use PDOException;

class VentaRepository
{
    private $db;

    public function __construct(\PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function guardar(Venta $venta): bool
    {
        if ($venta->getId() === null) {
            $stmt = $this->db->prepare("INSERT INTO ventas (repuesto_id, cliente_id, cantidad, fecha) VALUES (:repuesto_id, :cliente_id, :cantidad, :fecha)");
            $stmt->bindParam(':repuesto_id', $venta->getRepuesto()->getId(), PDO::PARAM_INT);
            $stmt->bindParam(':cliente_id', $venta->getUsuario()->getId(), PDO::PARAM_INT);
            $stmt->bindParam(':cantidad', $venta->getCantidad(), PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $venta->getFecha());
            try {
                $result = $stmt->execute();
                if ($result) {
                    $venta->setId($this->db->lastInsertId());
                }
                return $result;
            } catch (PDOException $e) {
                error_log("Error al guardar venta (INSERT): " . $e->getMessage());
                return false;
            }
        } else {
            $stmt = $this->db->prepare("UPDATE ventas SET repuesto_id = :repuesto_id, cliente_id = :cliente_id, cantidad = :cantidad, fecha = :fecha WHERE id = :id");
            $stmt->bindParam(':repuesto_id', $venta->getRepuesto()->getId(), PDO::PARAM_INT);
            $stmt->bindParam(':cliente_id', $venta->getUsuario()->getId(), PDO::PARAM_INT);
            $stmt->bindParam(':cantidad', $venta->getCantidad(), PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $venta->getFecha());
            $stmt->bindParam(':id', $venta->getId(), PDO::PARAM_INT);
            try {
                return $stmt->execute();
            } catch (PDOException $e) {
                error_log("Error al guardar venta (UPDATE): " . $e->getMessage());
                return false;
            }
        }
    }

    public function eliminar(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM ventas WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerTodos(): array
    {
        $stmt = $this->db->query("SELECT v.id, v.repuesto_id, v.cliente_id, v.cantidad, v.fecha, r.nombre as repuesto_nombre, r.precio, r.cantidad as repuesto_cantidad, c.nombre as cliente_nombre, c.dni FROM ventas v JOIN repuestos r ON v.repuesto_id = r.id JOIN personas c ON v.cliente_id = c.id");
        $ventasData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $ventas = [];
        foreach ($ventasData as $data) {
            $repuesto = new Repuesto($data['repuesto_id'], $data['repuesto_nombre'], $data['precio'], $data['repuesto_cantidad']);
            // Construct Usuario with available data: id, nombre, username (unknown), password (unknown), dni
            $usuario = new Usuario($data['cliente_id'], $data['cliente_nombre'], null, null, $data['dni']);
            $ventas[] = new Venta($data['id'], $repuesto, $usuario, $data['cantidad'], $data['fecha']);
        }
        return $ventas;
    }

    public function obtenerPorId(int $id): ?Venta
    {
        $stmt = $this->db->prepare("SELECT v.id, v.repuesto_id, v.cliente_id, v.cantidad, v.fecha, r.nombre as repuesto_nombre, r.precio, r.cantidad as repuesto_cantidad, c.nombre as cliente_nombre, c.dni FROM ventas v JOIN repuestos r ON v.repuesto_id = r.id JOIN personas c ON v.cliente_id = c.id WHERE v.id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $repuesto = new Repuesto($data['repuesto_id'], $data['repuesto_nombre'], $data['precio'], $data['repuesto_cantidad']);
            $usuario = new Usuario($data['cliente_id'], $data['cliente_nombre'], null, null, $data['dni']);
            return new Venta($data['id'], $repuesto, $usuario, $data['cantidad'], $data['fecha']);
        }
        return null;
    }
}
