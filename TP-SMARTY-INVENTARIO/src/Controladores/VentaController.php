<?php

class VentaController extends BaseController
{
    private $ventaRepository;
    private $ventaVista;
    private $repuestoRepository;
    private $clienteRepository;

    public function __construct()
    {
        parent::__construct();
        $this->ventaRepository = new VentaRepository($this->db);
        $this->ventaVista = $this->loadView('VentaVista');
        $this->repuestoRepository = new RepuestoRepository($this->db);
        $this->clienteRepository = new ClienteRepository($this->db);
    }

    public function showAll()
    {
        $ventas = $this->ventaRepository->obtenerTodos(); // Use repository method
        $this->ventaVista->showVentas($ventas);
    }

    public function showFormCreate()
    {
        $repuestos = $this->repuestoRepository->obtenerTodos(); // Use repository method
        $clientes = $this->clienteRepository->obtenerTodos(); // Use repository method
        $this->ventaVista->displayForm("", true, null, $repuestos, $clientes);
    }

    public function create()
    {
        require_once(SERVER_PATH . "/src/Validators/VentaValidator.php");
        $validator = new VentaValidator($this->repuestoRepository, $this->clienteRepository); // Pass repositories to validator
        $data = [
            'repuesto_id' => $_POST['repuesto_id'] ?? null,
            'cliente_id' => $_POST['cliente_id'] ?? null,
            'cantidad' => $_POST['cantidad'] ?? null
        ];

        if (!$validator->validate($data)) {
            $repuestos = $this->repuestoRepository->obtenerTodos();
            $clientes = $this->clienteRepository->obtenerTodos();
            $this->ventaVista->displayForm(implode(", ", $validator->getErrors()), false, null, $repuestos, $clientes);
            return;
        }

        $repuesto = $this->repuestoRepository->obtenerPorId($data['repuesto_id']);
        $cliente = $this->clienteRepository->obtenerPorId($data['cliente_id']);

        $newVenta = new Venta(null, $repuesto, $cliente, $data['cantidad'], date('Y-m-d H:i:s'));
        if ($this->ventaRepository->guardar($newVenta)) { // Use repository method
            // Decrement stock
            $this->repuestoRepository->restarCantidad($data['repuesto_id'], $data['cantidad']); // Use repository method
            $this->redirect(BASE_URL . 'ventas');
        } else {
            $repuestos = $this->repuestoRepository->obtenerTodos();
            $clientes = $this->clienteRepository->obtenerTodos();
            $this->ventaVista->displayForm("Error al crear la venta.", false, null, $repuestos, $clientes);
        }
    }
    public function showFormEdit($id)
    {
        $venta = $this->ventaRepository->obtenerPorId($id); // Use repository method
        if ($venta) {
            $repuestos = $this->repuestoRepository->obtenerTodos();
            $clientes = $this->clienteRepository->obtenerTodos();
            $this->ventaVista->displayForm("", true, $venta, $repuestos, $clientes);
        } else {
            $this->redirect(BASE_URL . 'ventas');
        }
    }

    public function update()
    {
        require_once(SERVER_PATH . "/src/Validators/VentaValidator.php");
        $validator = new VentaValidator($this->repuestoRepository, $this->clienteRepository); // Pass repositories to validator
        $data = [
            'id' => $_POST['id'] ?? null,
            'repuesto_id' => $_POST['repuesto_id'] ?? null,
            'cliente_id' => $_POST['cliente_id'] ?? null,
            'cantidad' => $_POST['cantidad'] ?? null
        ];

        if (!$validator->validate($data, true)) { // Pass true for isUpdate
            $repuestos = $this->repuestoRepository->obtenerTodos();
            $clientes = $this->clienteRepository->obtenerTodos();
            $venta = new Venta($data['id'], null, null, $data['cantidad'], date('Y-m-d H:i:s')); // Create a dummy Venta for displaying form
            $this->ventaVista->displayForm(implode(", ", $validator->getErrors()), false, $venta, $repuestos, $clientes);
            return;
        }

        $repuesto = $this->repuestoRepository->obtenerPorId($data['repuesto_id']);
        $cliente = $this->clienteRepository->obtenerPorId($data['cliente_id']);

        $venta = new Venta($data['id'], $repuesto, $cliente, $data['cantidad'], date('Y-m-d H:i:s'));
        if ($this->ventaRepository->guardar($venta)) { // Use repository method
            $this->redirect(BASE_URL . 'ventas');
        } else {
            $repuestos = $this->repuestoRepository->obtenerTodos();
            $clientes = $this->clienteRepository->obtenerTodos();
            $this->ventaVista->displayForm("Error al actualizar la venta.", false, $venta, $repuestos, $clientes);
        }
    }
    public function showConfirmDelete($id)
    {
        $venta = $this->ventaRepository->obtenerPorId($id); // Use repository method
        if ($venta) {
            // Assuming there's a method to display a confirmation view in VentaVista
            // For now, we'll just redirect to avoid further errors, but this should be a confirmation view.
            $this->ventaVista->showConfirmDelete($venta); // Placeholder
        } else {
            $this->redirect(BASE_URL . 'ventas');
        }
    }

    public function delete($id)
    {
        if ($this->ventaRepository->eliminar($id)) { // Assuming an 'eliminar' method in VentaRepository
            $this->redirect(BASE_URL . 'ventas');
        } else {
            // Handle error, maybe redirect with an error message
            $this->redirect(BASE_URL . 'ventas'); // Or to an error page
        }
    }
}
