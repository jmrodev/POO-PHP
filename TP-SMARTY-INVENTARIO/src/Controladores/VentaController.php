<?php

class VentaController {
    private $ventaRepository; // Use repository instead of model directly for DB ops
    private $ventaVista;
    private $repuestoRepository; // Use repository instead of model directly for DB ops
    private $clienteRepository; // Use repository instead of model directly for DB ops

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

        $this->ventaRepository = new VentaRepository($pdo); // Pass PDO to repository
        $this->ventaVista = new VentaVista();
        $this->repuestoRepository = new RepuestoRepository($pdo); // Pass PDO to repository
        $this->clienteRepository = new ClienteRepository($pdo); // Pass PDO to repository
    }

    public function showAll() {
        $ventas = $this->ventaRepository->obtenerTodos(); // Use repository method
        $this->ventaVista->showVentas($ventas);
    }

    public function showFormCreate() {
        $repuestos = $this->repuestoRepository->obtenerTodos(); // Use repository method
        $clientes = $this->clienteRepository->obtenerTodos(); // Use repository method
        $this->ventaVista->displayForm("", true, null, $repuestos, $clientes);
    }

            public function create() {
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
                    header('Location: ' . BASE_URL . 'ventas');
                    exit();
                } else {
                    $repuestos = $this->repuestoRepository->obtenerTodos();
                    $clientes = $this->clienteRepository->obtenerTodos();
                    $this->ventaVista->displayForm("Error al crear la venta.", false, null, $repuestos, $clientes);
                }
            }
    public function showFormEdit($id) {
        $venta = $this->ventaRepository->obtenerPorId($id); // Use repository method
        if ($venta) {
            $repuestos = $this->repuestoRepository->obtenerTodos();
            $clientes = $this->clienteRepository->obtenerTodos();
            $this->ventaVista->displayForm("", true, $venta, $repuestos, $clientes);
        } else {
            header('Location: ' . BASE_URL . 'ventas');
            exit();
        }
    }

            public function update() {
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
                    header('Location: ' . BASE_URL . 'ventas');
                    exit();
                } else {
                    $repuestos = $this->repuestoRepository->obtenerTodos();
                    $clientes = $this->clienteRepository->obtenerTodos();
                    $this->ventaVista->displayForm("Error al actualizar la venta.", false, $venta, $repuestos, $clientes);
                }
            }
    public function showConfirmDelete($id) {
        $venta = $this->ventaRepository->obtenerPorId($id); // Use repository method
        if ($venta) {
            $this->ventaVista->displayConfirmDelete($venta);
        } else {
            header('Location: ' . BASE_URL . 'ventas');
            exit();
        }
    }

    public function delete($id) {
        if ($this->ventaRepository->eliminar($id)) { // Use repository method
            header('Location: ' . BASE_URL . 'ventas');
            exit();
        } else {
            header('Location: ' . BASE_URL . 'ventas');
            exit();
        }
    }
}
?>
