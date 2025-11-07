<?php

class ClienteController extends BaseController
{
    private $clienteRepository;
    private $clienteVista;

    public function __construct()
    {
        parent::__construct();
        $this->clienteRepository = new ClienteRepository($this->db);
        $this->clienteVista = $this->loadView('ClienteVista');
    }

    public function showAll()
    {
        $clientes = $this->clienteRepository->obtenerTodos(); // Use repository method
        $this->clienteVista->showClientes($clientes);
    }

    public function showFormCreate()
    {
        $this->clienteVista->displayForm();
    }

    public function create()
    {
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
        if ($this->clienteRepository->guardar($newCliente)) { // Assuming this was the missing condition
            $this->redirect('/clientes');
        } else {
            $this->clienteVista->displayForm("Error al crear el cliente.", false);
        }
    }

    public function showFormEdit($id)
    {
        $cliente = $this->clienteRepository->obtenerPorId($id); // Use repository method
        if ($cliente) {
            $this->clienteVista->displayForm("", true, $cliente);
        } else {
            $this->redirect(BASE_URL . 'clientes');
        }
    }

    public function update()
    {
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
            $this->redirect('/clientes');
        } else {
            $this->clienteVista->displayForm("Error al actualizar el cliente.", false, $cliente);
        }
    }

    public function showConfirmDelete($id)
    {
        $cliente = $this->clienteRepository->obtenerPorId($id); // Use repository method
        if ($cliente) {
            // Assuming there's a method to display a confirmation view in ClienteVista
            $this->clienteVista->showConfirmDelete($cliente);
        } else {
            $this->redirect(BASE_URL . 'clientes');
        }
    }

    public function delete($id)
    {
        if ($this->clienteRepository->eliminar($id)) { // Use repository method
            header('Location: ' . BASE_URL . 'clientes');
            exit();
        } else {
            $this->redirect(BASE_URL . 'clientes');
        }
    }
}
