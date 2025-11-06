<?php

class ClienteController {
    private $clienteRepository; // Use repository instead of model directly for DB ops
    private $clienteVista;

    public function __construct()
    {
        $config = [
            'servername' => '127.0.0.1',
            'username' => 'root',
            'password' => 'jmro1975',
            'dbname' => 'inventarioRepuestos'
        ];

        try {
            $pdo = new PDO("mysql:host=" . $config['servername'] . ";dbname=" . $config['dbname'] . ";charset=utf8mb4", $config['username'], $config['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }

        $this->clienteRepository = new ClienteRepository($pdo); // Pass PDO to repository
        $this->clienteVista = new ClienteVista();
    }

    public function showAll() {
        $clientes = $this->clienteRepository->obtenerTodos(); // Use repository method
        $this->clienteVista->showClientes($clientes);
    }

    public function showFormCreate() {
        $this->clienteVista->displayForm();
    }

    public function create() {
        $validator = new ClienteValidator();
        $data = ['nombre' => $_POST['nombre'] ?? '', 'dni' => $_POST['dni'] ?? ''];

        if (!$validator->validate($data)) {
            $this->clienteVista->displayForm(implode(", ", $validator->getErrors()), false);
            return;
        }

        $generatedUsername = strtolower(str_replace(' ', '', $data['nombre']));
        if (empty($generatedUsername)) {
            $generatedUsername = 'cliente_' . uniqid(); // Generate a unique username
        }
        $temporaryPassword = 'password'; 
        $hashed_password = password_hash($temporaryPassword, PASSWORD_DEFAULT);

        $newCliente = new Cliente(null, $data['nombre'], $generatedUsername, $hashed_password, $data['dni']);
            header('Location: /clientes');
            exit();
        } else {
            $this->clienteVista->displayForm("Error al crear el cliente.", false);
        }
    }

    public function showFormEdit($id) {
        $cliente = $this->clienteRepository->obtenerPorId($id); // Use repository method
        if ($cliente) {
            $this->clienteVista->displayForm("", true, $cliente);
        } else {
            header('Location: ' . BASE_URL . 'clientes');
            exit();
        }
    }

    public function update() {
        $validator = new ClienteValidator();
        $data = ['id' => $_POST['id'] ?? null, 'nombre' => $_POST['nombre'] ?? '', 'dni' => $_POST['dni'] ?? ''];

        if (!$validator->validate($data, true)) { // Pass true for isUpdate
            $existingCliente = $this->clienteRepository->obtenerPorId($data['id']);
            $cliente = new Cliente($data['id'], $data['nombre'], $existingCliente->getUsername(), $existingCliente->getPassword(), $data['dni']); // Re-instantiate for displaying form with current data
            $this->clienteVista->displayForm(implode(", ", $validator->getErrors()), false, $cliente);
            return;
        }

        $existingCliente = $this->clienteRepository->obtenerPorId($data['id']);
        $cliente = new Cliente($data['id'], $data['nombre'], $existingCliente->getUsername(), $existingCliente->getPassword(), $data['dni']);
        if ($this->clienteRepository->guardar($cliente)) { // Use repository method
            header('Location: /clientes');
            exit();
        } else {
            $this->clienteVista->displayForm("Error al actualizar el cliente.", false, $cliente);
        }
    }

    public function showConfirmDelete($id) {
        $cliente = $this->clienteRepository->obtenerPorId($id); // Use repository method
        if ($cliente) {
            $this->clienteVista->displayConfirmDelete($cliente);
        } else {
            header('Location: ' . BASE_URL . 'clientes');
            exit();
        }
    }

    public function delete($id) {
        if ($this->clienteRepository->eliminar($id)) { // Use repository method
            header('Location: ' . BASE_URL . 'clientes');
            exit();
        } else {
            // Optionally display an error message on the clients list page
            header('Location: ' . BASE_URL . 'clientes');
            exit();
        }
    }
}
