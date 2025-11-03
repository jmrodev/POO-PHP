<?php

include_once __DIR__ . '/Database.php';
include_once __DIR__ . '/Repuesto.php';
include_once __DIR__ . '/Cliente.php';

class Venta {
    private $id;
    private $repuesto;
    private $cliente;
    private $cantidad;
    private $fecha;

    public function __construct(?int $id = null, ?Repuesto $repuesto = null, ?Cliente $cliente = null, ?int $cantidad = null, ?string $fecha = null) {
        $this->id = $id;
        $this->repuesto = $repuesto;
        $this->cliente = $cliente;
        $this->cantidad = $cantidad;
        $this->fecha = $fecha;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getRepuesto(): Repuesto {
        return $this->repuesto;
    }

    public function getCliente(): Cliente {
        return $this->cliente;
    }

    public function getCantidad(): int {
        return $this->cantidad;
    }

    public function getFecha(): string {
        return $this->fecha;
    }

    public function guardar(): bool {
        $db = Database::getInstance()->getConnection();
        if ($this->id === null) {
            $stmt = $db->prepare("INSERT INTO ventas (repuesto_id, cliente_id, cantidad, fecha) VALUES (:repuesto_id, :cliente_id, :cantidad, :fecha)");
            $stmt->bindParam(':repuesto_id', $this->repuesto->getId(), PDO::PARAM_INT);
            $stmt->bindParam(':cliente_id', $this->cliente->getId(), PDO::PARAM_INT);
            $stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $this->fecha);
            try {
                $result = $stmt->execute();
                if ($result) {
                    $this->id = $db->lastInsertId();
                }
                return $result;
            } catch (PDOException $e) {
                error_log("Error al guardar venta (INSERT): " . $e->getMessage());
                return false;
            }
        } else {
            $stmt = $db->prepare("UPDATE ventas SET repuesto_id = :repuesto_id, cliente_id = :cliente_id, cantidad = :cantidad, fecha = :fecha WHERE id = :id");
            $stmt->bindParam(':repuesto_id', $this->repuesto->getId(), PDO::PARAM_INT);
            $stmt->bindParam(':cliente_id', $this->cliente->getId(), PDO::PARAM_INT);
            $stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $this->fecha);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            try {
                return $stmt->execute();
            } catch (PDOException $e) {
                error_log("Error al guardar venta (UPDATE): " . $e->getMessage());
                return false;
            }
        }
    }

    public function eliminar(): bool {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM ventas WHERE id = :id");
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerTodos(): array {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT v.id, v.repuesto_id, v.cliente_id, v.cantidad, v.fecha, r.nombre as repuesto_nombre, r.precio, r.cantidad as repuesto_cantidad, c.nombre as cliente_nombre, c.dni FROM ventas v JOIN repuestos r ON v.repuesto_id = r.id JOIN clientes c ON v.cliente_id = c.id");
        $ventasData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $ventas = [];
        foreach ($ventasData as $data) {
            $repuesto = new Repuesto($data['repuesto_id'], $data['repuesto_nombre'], $data['precio'], $data['repuesto_cantidad']);
            $cliente = new Cliente($data['cliente_id'], $data['cliente_nombre'], $data['dni']);
            $ventas[] = new Venta($data['id'], $repuesto, $cliente, $data['cantidad'], $data['fecha']);
        }
        return $ventas;
    }

    public function obtenerPorId(int $id): ?Venta {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT v.id, v.repuesto_id, v.cliente_id, v.cantidad, v.fecha, r.nombre as repuesto_nombre, r.precio, r.cantidad as repuesto_cantidad, c.nombre as cliente_nombre, c.dni FROM ventas v JOIN repuestos r ON v.repuesto_id = r.id JOIN clientes c ON v.cliente_id = c.id WHERE v.id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $repuesto = new Repuesto($data['repuesto_id'], $data['repuesto_nombre'], $data['precio'], $data['repuesto_cantidad']);
            $cliente = new Cliente($data['cliente_id'], $data['cliente_nombre'], $data['dni']);
            return new Venta($data['id'], $repuesto, $cliente, $data['cantidad'], $data['fecha']);
        }
        return null;
    }
}
