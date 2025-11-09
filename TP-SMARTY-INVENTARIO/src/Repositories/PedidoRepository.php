<?php

namespace App\Repositories;

use App\Modelos\Pedido;
use App\Modelos\Usuario; // Assuming Usuario model is used for fetching user details
use App\Modelos\Repuesto; // Assuming Repuesto model is used for fetching repuesto details
use App\Modelos\DetallePedido; // Assuming DetallePedido model is used
use PDO;
use PDOException;

class PedidoRepository
{
    private PDO $db;
    private RepuestoRepository $repuestoRepository;
    private PersonaRepository $personaRepository;

    public function __construct(PDO $pdo, RepuestoRepository $repuestoRepository, PersonaRepository $personaRepository)
    {
        $this->db = $pdo;
        $this->repuestoRepository = $repuestoRepository;
        $this->personaRepository = $personaRepository;
    }

    public function guardar(Pedido $pedido): bool
    {
        if ($pedido->getId() === null) {
            // Insert new pedido
            $stmt = $this->db->prepare("INSERT INTO pedidos (usuario_id, fecha_pedido, total, estado) VALUES (:usuario_id, :fecha_pedido, :total, :estado)");
            $stmt->bindValue(':usuario_id', $pedido->getUsuarioId(), PDO::PARAM_INT);
            $stmt->bindValue(':fecha_pedido', $pedido->getFechaPedido());
            $stmt->bindValue(':total', $pedido->getTotal());
            $stmt->bindValue(':estado', $pedido->getEstado());

            try {
                $result = $stmt->execute();
                if ($result) {
                    $pedido->setId((int)$this->db->lastInsertId());
                    // Save associated detalle_pedido
                    foreach ($pedido->getDetalles() as $detalle) {
                        $detalle->setPedidoId($pedido->getId());
                        $this->guardarDetallePedido($detalle);
                    }
                }
                return $result;
            } catch (PDOException $e) {
                error_log("Error al guardar pedido (INSERT): " . $e->getMessage());
                return false;
            }
        } else {
            // Update existing pedido
            $stmt = $this->db->prepare("UPDATE pedidos SET usuario_id = :usuario_id, fecha_pedido = :fecha_pedido, total = :total, estado = :estado WHERE id = :id");
            $stmt->bindValue(':usuario_id', $pedido->getUsuarioId(), PDO::PARAM_INT);
            $stmt->bindValue(':fecha_pedido', $pedido->getFechaPedido());
            $stmt->bindValue(':total', $pedido->getTotal());
            $stmt->bindValue(':estado', $pedido->getEstado());
            $stmt->bindValue(':id', $pedido->getId(), PDO::PARAM_INT);

            try {
                $result = $stmt->execute();
                // For simplicity, updating details is not handled here.
                // A more robust solution would involve deleting old details and inserting new ones,
                // or comparing and updating existing ones.
                return $result;
            } catch (PDOException $e) {
                error_log("Error al guardar pedido (UPDATE): " . $e->getMessage());
                return false;
            }
        }
    }

    private function guardarDetallePedido(DetallePedido $detalle): bool
    {
        $stmt = $this->db->prepare("INSERT INTO detalle_pedido (pedido_id, repuesto_id, cantidad, precio_unitario) VALUES (:pedido_id, :repuesto_id, :cantidad, :precio_unitario)");
        $stmt->bindValue(':pedido_id', $detalle->getPedidoId(), PDO::PARAM_INT);
        $stmt->bindValue(':repuesto_id', $detalle->getRepuestoId(), PDO::PARAM_INT);
        $stmt->bindValue(':cantidad', $detalle->getCantidad(), PDO::PARAM_INT);
        $stmt->bindValue(':precio_unitario', $detalle->getPrecioUnitario());

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al guardar detalle de pedido: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar(int $id): bool
    {
        // Start a transaction
        $this->db->beginTransaction();
        try {
            // First delete associated detalle_pedido
            $stmtDetalle = $this->db->prepare("DELETE FROM detalle_pedido WHERE pedido_id = :pedido_id");
            $stmtDetalle->bindValue(':pedido_id', $id, PDO::PARAM_INT);
            $stmtDetalle->execute();

            // Then delete the pedido
            $stmtPedido = $this->db->prepare("DELETE FROM pedidos WHERE id = :id");
            $stmtPedido->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmtPedido->execute();

            $this->db->commit();
            return $result;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error al eliminar pedido: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerTodos(): array
    {
        $stmt = $this->db->query("SELECT p.id, p.usuario_id, p.fecha_pedido, p.total, p.estado, u.nombre as usuario_nombre, u.username, u.role, u.dni FROM pedidos p JOIN personas u ON p.usuario_id = u.id ORDER BY p.fecha_pedido DESC");
        $pedidosData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pedidos = [];
        foreach ($pedidosData as $data) {
            $usuario = new Usuario($data['usuario_id'], $data['usuario_nombre'], $data['username'], null, $data['dni'], $data['role']);
            $pedido = new Pedido($data['id'], $data['usuario_id'], $data['fecha_pedido'], $data['total'], $data['estado'], $usuario);
            $pedido->setDetalles($this->obtenerDetallesPorPedidoId($pedido->getId()));
            $pedidos[] = $pedido;
        }
        return $pedidos;
    }

    public function obtenerPorId(int $id): ?Pedido
    {
        $stmt = $this->db->prepare("SELECT p.id, p.usuario_id, p.fecha_pedido, p.total, p.estado, u.nombre as usuario_nombre, u.username, u.role, u.dni FROM pedidos p JOIN personas u ON p.usuario_id = u.id WHERE p.id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $usuario = new Usuario($data['usuario_id'], $data['usuario_nombre'], $data['username'], null, $data['dni'], $data['role']);
            $pedido = new Pedido($data['id'], $data['usuario_id'], $data['fecha_pedido'], $data['total'], $data['estado'], $usuario);
            $pedido->setDetalles($this->obtenerDetallesPorPedidoId($pedido->getId()));
            return $pedido;
        }
        return null;
    }

    public function obtenerPedidosPorUsuarioId(int $usuarioId): array
    {
        $stmt = $this->db->prepare("SELECT p.id, p.usuario_id, p.fecha_pedido, p.total, p.estado, u.nombre as usuario_nombre, u.username, u.role, u.dni FROM pedidos p JOIN personas u ON p.usuario_id = u.id WHERE p.usuario_id = :usuario_id ORDER BY p.fecha_pedido DESC");
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();
        $pedidosData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pedidos = [];
        foreach ($pedidosData as $data) {
            $usuario = new Usuario($data['usuario_id'], $data['usuario_nombre'], $data['username'], null, $data['dni'], $data['role']);
            $pedido = new Pedido($data['id'], $data['usuario_id'], $data['fecha_pedido'], $data['total'], $data['estado'], $usuario);
            $pedido->setDetalles($this->obtenerDetallesPorPedidoId($pedido->getId()));
            $pedidos[] = $pedido;
        }
        return $pedidos;
    }

    private function obtenerDetallesPorPedidoId(int $pedidoId): array
    {
        $stmt = $this->db->prepare("SELECT dp.id, dp.pedido_id, dp.repuesto_id, dp.cantidad, dp.precio_unitario, r.nombre as repuesto_nombre, r.precio as repuesto_precio, r.cantidad as repuesto_stock, r.imagen FROM detalle_pedido dp JOIN repuestos r ON dp.repuesto_id = r.id WHERE dp.pedido_id = :pedido_id");
        $stmt->bindValue(':pedido_id', $pedidoId, PDO::PARAM_INT);
        $stmt->execute();
        $detallesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $detalles = [];
        foreach ($detallesData as $data) {
            $repuesto = new Repuesto($data['repuesto_id'], $data['repuesto_nombre'], $data['repuesto_precio'], $data['repuesto_stock'], $data['imagen']);
            $detalles[] = new DetallePedido($data['id'], $data['pedido_id'], $data['repuesto_id'], $data['cantidad'], $data['precio_unitario'], $repuesto);
        }
        return $detalles;
    }
}
