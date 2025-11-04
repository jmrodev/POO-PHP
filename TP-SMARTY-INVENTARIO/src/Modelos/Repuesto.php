<?php
include_once __DIR__ . '/Database.php';

class Repuesto {

    protected $id;
    protected $nombre;
    protected $precio;
    protected $cantidad;
    protected $imagen;

    public function __construct($id = null, $nombre = null, $precio = null, $cantidad = null, $imagen = null) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->cantidad = $cantidad;
        $this->imagen = $imagen;
    }

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function getCantidad() {
        return $this->cantidad;
    }

    public function getImagen() {
        return $this->imagen;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    public function setImagen($imagen) {
        $this->imagen = $imagen;
    }

    public function obtenerTodos() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT id, nombre, precio, cantidad, imagen FROM repuestos");
        $repuestosData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $repuestos = [];
        foreach ($repuestosData as $data) {
            $repuestos[] = new Repuesto($data['id'], $data['nombre'], $data['precio'], $data['cantidad'], $data['imagen']);
        }
        return $repuestos;
    }

    public function obtenerPorId($id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id, nombre, precio, cantidad, imagen FROM repuestos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return new Repuesto($data['id'], $data['nombre'], $data['precio'], $data['cantidad'], $data['imagen']);
        }
        return null;
    }

    public function guardar() {
        $db = Database::getInstance()->getConnection();
        if ($this->id === null) {
            $stmt = $db->prepare("INSERT INTO repuestos (nombre, precio, cantidad, imagen) VALUES (:nombre, :precio, :cantidad, :imagen)");
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':precio', $this->precio);
            $stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_INT);
            $stmt->bindParam(':imagen', $this->imagen);
            $result = $stmt->execute();
            if ($result) {
                $this->id = $db->lastInsertId();
            }
            return $result;
        } else {
            $stmt = $db->prepare("UPDATE repuestos SET nombre = :nombre, precio = :precio, cantidad = :cantidad, imagen = :imagen WHERE id = :id");
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':precio', $this->precio);
            $stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_INT);
            $stmt->bindParam(':imagen', $this->imagen);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            return $stmt->execute();
        }
    }

    public function eliminar() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM repuestos WHERE id = :id");
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function restarCantidad($id, $cantidad) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE repuestos SET cantidad = cantidad - :cantidad WHERE id = :id AND cantidad >= :cantidad");
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerCantidad($id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT cantidad FROM repuestos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['cantidad'] : 0;
    }
}