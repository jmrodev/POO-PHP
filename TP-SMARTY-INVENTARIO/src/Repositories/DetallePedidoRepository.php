<?php

namespace App\Repositories;

use App\Modelos\DetallePedido;
use PDO;
use PDOException;

class DetallePedidoRepository
{
    private PDO $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function guardar(DetallePedido $detalle): bool
    {
        if ($detalle->getId() === null) {
            $stmt = $this->db->prepare("INSERT INTO detalle_pedido (pedido_id, repuesto_id, cantidad, precio_unitario) VALUES (:pedido_id, :repuesto_id, :cantidad, :precio_unitario)");
            $stmt->bindValue(':pedido_id', $detalle->getPedidoId(), PDO::PARAM_INT);
            $stmt->bindValue(':repuesto_id', $detalle->getRepuestoId(), PDO::PARAM_INT);
            $stmt->bindValue(':cantidad', $detalle->getCantidad(), PDO::PARAM_INT);
            $stmt->bindValue(':precio_unitario', $detalle->getPrecioUnitario());

            try {
                $result = $stmt->execute();
                if ($result) {
                    $detalle->setId((int)$this->db->lastInsertId());
                }
                return $result;
            } catch (PDOException $e) {
                error_log("Error al guardar detalle de pedido (INSERT): " . $e->getMessage());
                return false;
            }
        } else {
            $stmt = $this->db->prepare("UPDATE detalle_pedido SET pedido_id = :pedido_id, repuesto_id = :repuesto_id, cantidad = :cantidad, precio_unitario = :precio_unitario WHERE id = :id");
            $stmt->bindValue(':pedido_id', $detalle->getPedidoId(), PDO::PARAM_INT);
            $stmt->bindValue(':repuesto_id', $detalle->getRepuestoId(), PDO::PARAM_INT);
            $stmt->bindValue(':cantidad', $detalle->getCantidad(), PDO::PARAM_INT);
            $stmt->bindValue(':precio_unitario', $detalle->getPrecioUnitario());
            $stmt->bindValue(':id', $detalle->getId(), PDO::PARAM_INT);

            try {
                return $stmt->execute();
            } catch (PDOException $e) {
                error_log("Error al guardar detalle de pedido (UPDATE): " . $e->getMessage());
                return false;
            }
        }
    }

    public function eliminar(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM detalle_pedido WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar detalle de pedido: " . $e->getMessage());
            return false;
        }
    }

    // You might add methods like findById, findByPedidoId if needed for direct access
}
