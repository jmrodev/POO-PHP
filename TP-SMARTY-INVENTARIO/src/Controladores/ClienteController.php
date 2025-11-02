<?php
require_once(SERVER_PATH."/src/Modelos/Cliente.php");
require_once(SERVER_PATH."/src/Vistas/ClienteVista.php");

class ClienteController {
    private $clienteModel;
    private $clienteVista;

    public function __construct() {
        $this->clienteModel = new Cliente();
        $this->clienteVista = new ClienteVista();
    }

    public function showAll() {
        $clientes = $this->clienteModel->obtenerTodos();
        $this->clienteVista->showClientes($clientes);
    }

    public function showFormCreate() {
        $this->clienteVista->displayForm();
    }

    public function create() {
        $nombre = $_POST['nombre'] ?? '';
        $dni = $_POST['dni'] ?? '';

        if (empty($nombre) || empty($dni)) {
            $this->clienteVista->displayForm("Todos los campos son obligatorios.", false);
            return;
        }

        $newCliente = new Cliente(null, $nombre, $dni);
        if ($newCliente->guardar()) {
            header('Location: ' . BASE_URL . 'clientes');
            exit();
        } else {
            $this->clienteVista->displayForm("Error al crear el cliente.", false);
        }
    }

    public function showFormEdit($id) {
        $cliente = $this->clienteModel->obtenerPorId($id);
        if ($cliente) {
            $this->clienteVista->displayForm("", true, $cliente);
        } else {
            header('Location: ' . BASE_URL . 'clientes');
            exit();
        }
    }

    public function update() {
        $id = $_POST['id'] ?? null;
        $nombre = $_POST['nombre'] ?? '';
        $dni = $_POST['dni'] ?? '';

        if (empty($id) || empty($nombre) || empty($dni)) {
            $this->clienteVista->displayForm("Todos los campos son obligatorios.", false);
            return;
        }

        $cliente = new Cliente($id, $nombre, $dni);
        if ($cliente->guardar()) {
            header('Location: ' . BASE_URL . 'clientes');
            exit();
        } else {
            $this->clienteVista->displayForm("Error al actualizar el cliente.", false, $cliente);
        }
    }

    public function showConfirmDelete($id) {
        $cliente = $this->clienteModel->obtenerPorId($id);
        if ($cliente) {
            $this->clienteVista->displayConfirmDelete($cliente);
        } else {
            header('Location: ' . BASE_URL . 'clientes');
            exit();
        }
    }

    public function delete($id) {
        $cliente = new Cliente($id);
        if ($cliente->eliminar()) {
            header('Location: ' . BASE_URL . 'clientes');
            exit();
        } else {
            // Optionally display an error message on the clients list page
            header('Location: ' . BASE_URL . 'clientes');
            exit();
        }
    }
}
?>