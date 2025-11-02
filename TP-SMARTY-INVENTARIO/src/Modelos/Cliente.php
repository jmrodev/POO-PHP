<?php
include_once __DIR__ . '/Database.php';
include_once __DIR__ . '/Venta.php';

class Cliente {

    protected $id;
    protected $nombre;
    protected $dni;
    protected $ventas = [];

    public function __construct($id = null, $nombre = null, $dni = null) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->dni = $dni;
        $this->ventas = [];
    }

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getDni() {
        return $this->dni;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setDni($dni) {
        $this->dni = $dni;
    }

    public function getVentas(): array {
        return $this->ventas;
    }

    public function addVenta ($venta): void {
        $this->ventas[] = $venta;
    }

    public function obtenerTodos() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT id, nombre, dni FROM clientes");
        $clientesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $clientes = [];
        foreach ($clientesData as $data) {
            $clientes[] = new Cliente($data['id'], $data['nombre'], $data['dni']);
        }
        return $clientes;
    }

    public function obtenerPorId($id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id, nombre, dni FROM clientes WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return new Cliente($data['id'], $data['nombre'], $data['dni']);
        }
        return null;
    }

    public function guardar() {
        $db = Database::getInstance()->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            if ($this->id === null) {
                $stmt = $db->prepare("INSERT INTO clientes (nombre, dni) VALUES (:nombre, :dni)");
                $stmt->bindParam(':nombre', $this->nombre);
                $stmt->bindParam(':dni', $this->dni);
                $result = $stmt->execute();
                if ($result) {
                    $this->id = $db->lastInsertId();
                }
                return $result;
            } else {
                $stmt = $db->prepare("UPDATE clientes SET nombre = :nombre, dni = :dni WHERE id = :id");
                $stmt->bindParam(':nombre', $this->nombre);
                $stmt->bindParam(':dni', $this->dni);
                $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
                return $stmt->execute();
            }
        } catch (PDOException $e) {
            error_log("Error al guardar cliente: " . $e->getMessage());
            echo "Error al guardar cliente: " . $e->getMessage();
            return false;
        } finally {
        }
    }

    public function eliminar() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM clientes WHERE id = :id");
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
