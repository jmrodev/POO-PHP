<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(SERVER_PATH."/src/Modelos/Venta.php");
require_once(SERVER_PATH."/src/Modelos/Repuesto.php");
require_once(SERVER_PATH."/src/Modelos/Cliente.php");
require_once(SERVER_PATH."/src/Vistas/VentaVista.php");

class VentaController {
    private $ventaModel;
    private $ventaVista;
    private $repuestoModel;
    private $clienteModel;

    public function __construct() {
        $this->ventaModel = new Venta();
        $this->ventaVista = new VentaVista();
        $this->repuestoModel = new Repuesto();
        $this->clienteModel = new Cliente();
    }

    public function showAll() {
        $ventas = $this->ventaModel->obtenerTodos();
        $this->ventaVista->showVentas($ventas);
    }

    public function showFormCreate() {
        $repuestos = $this->repuestoModel->obtenerTodos();
        $clientes = $this->clienteModel->obtenerTodos();
        $this->ventaVista->displayForm("", true, null, $repuestos, $clientes);
    }

    public function create() {
        $repuesto_id = $_POST['repuesto_id'] ;
        $cliente_id = $_POST['cliente_id'];
        $cantidad = $_POST['cantidad'];

        if (empty($repuesto_id) || empty($cliente_id) || empty($cantidad)) {
            $repuestos = $this->repuestoModel->obtenerTodos();
            $clientes = $this->clienteModel->obtenerTodos();
            $this->ventaVista->displayForm("Todos los campos son obligatorios.", false, null, $repuestos, $clientes);
            return;
        }

        $repuesto = $this->repuestoModel->obtenerPorId($repuesto_id);
        $cliente = $this->clienteModel->obtenerPorId($cliente_id);

        if (!$repuesto || !$cliente) {
            $repuestos = $this->repuestoModel->obtenerTodos();
            $clientes = $this->clienteModel->obtenerTodos();
            $this->ventaVista->displayForm("Repuesto o Cliente no válido.", false, null, $repuestos, $clientes);
            return;
        }

        // Check if there is enough stock
        $currentStock = $this->repuestoModel->obtenerCantidad($repuesto_id);
        if ($currentStock < $cantidad) {
            $repuestos = $this->repuestoModel->obtenerTodos();
            $clientes = $this->clienteModel->obtenerTodos();
            $this->ventaVista->displayForm("No hay suficiente stock para el repuesto seleccionado. Stock actual: " . $currentStock, false, null, $repuestos, $clientes);
            return;
        }

        $newVenta = new Venta(null, $repuesto, $cliente, $cantidad, date('Y-m-d H:i:s'));
        if ($newVenta->guardar()) {
            // Decrement stock
            $this->repuestoModel->restarCantidad($repuesto_id, $cantidad);
            header('Location: ' . BASE_URL . 'ventas');
            exit();
        } else {
            $repuestos = $this->repuestoModel->obtenerTodos();
            $clientes = $this->clienteModel->obtenerTodos();
            $this->ventaVista->displayForm("Error al crear la venta.", false, null, $repuestos, $clientes);
        }
    }

    public function showFormEdit($id) {
        $venta = $this->ventaModel->obtenerPorId($id);
        if ($venta) {
            $repuestos = $this->repuestoModel->obtenerTodos();
            $clientes = $this->clienteModel->obtenerTodos();
            $this->ventaVista->displayForm("", true, $venta, $repuestos, $clientes);
        } else {
            header('Location: ' . BASE_URL . 'ventas');
            exit();
        }
    }

    public function update() {
        $id = $_POST['id'] ?? null;
        $repuesto_id = $_POST['repuesto_id'] ?? null;
        $cliente_id = $_POST['cliente_id'] ?? null;
        $cantidad = $_POST['cantidad'] ?? null;

        if (empty($id) || empty($repuesto_id) || empty($cliente_id) || empty($cantidad)) {
            $repuestos = $this->repuestoModel->obtenerTodos();
            $clientes = $this->clienteModel->obtenerTodos();
            $this->ventaVista->displayForm("Todos los campos son obligatorios.", false, null, $repuestos, $clientes);
            return;
        }

        $repuesto = $this->repuestoModel->obtenerPorId($repuesto_id);
        $cliente = $this->clienteModel->obtenerPorId($cliente_id);

        if (!$repuesto || !$cliente) {
            $repuestos = $this->repuestoModel->obtenerTodos();
            $clientes = $this->clienteModel->obtenerTodos();
            $this->ventaVista->displayForm("Repuesto o Cliente no válido.", false, null, $repuestos, $clientes);
            return;
        }

        $venta = new Venta($id, $repuesto, $cliente, $cantidad, date('Y-m-d H:i:s'));
        if ($venta->guardar()) {
            header('Location: ' . BASE_URL . 'ventas');
            exit();
        } else {
            $repuestos = $this->repuestoModel->obtenerTodos();
            $clientes = $this->clienteModel->obtenerTodos();
            $this->ventaVista->displayForm("Error al actualizar la venta.", false, $venta, $repuestos, $clientes);
        }
    }

    public function showConfirmDelete($id) {
        $venta = $this->ventaModel->obtenerPorId($id);
        if ($venta) {
            $this->ventaVista->displayConfirmDelete($venta);
        } else {
            header('Location: ' . BASE_URL . 'ventas');
            exit();
        }
    }

    public function delete($id) {
        $venta = new Venta($id);
        if ($venta->eliminar()) {
            header('Location: ' . BASE_URL . 'ventas');
            exit();
        } else {
            // Optionally display an error message on the ventas list page
            header('Location: ' . BASE_URL . 'ventas');
            exit();
        }
    }
}
?>
