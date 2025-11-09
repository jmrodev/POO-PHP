<?php

namespace App\Controladores;

use App\Modelos\Venta;
use App\Modelos\Repuesto;
use App\Modelos\Usuario;
use App\Repositories\VentaRepository;
use App\Repositories\RepuestoRepository;
use App\Repositories\PersonaRepository;
use App\Validators\VentaValidator;
use Smarty;

use App\Services\AuthService; // Add this use statement

class VentaController extends BaseController
{
    private VentaRepository $ventaRepository;
    private RepuestoRepository $repuestoRepository;
    private PersonaRepository $personaRepository;
    private AuthService $authService; // Add this property

    public function __construct(\Smarty $smarty, VentaRepository $ventaRepository, RepuestoRepository $repuestoRepository, PersonaRepository $personaRepository, AuthService $authService)
    {
        parent::__construct($smarty);
        $this->ventaRepository = $ventaRepository;
        $this->repuestoRepository = $repuestoRepository;
        $this->personaRepository = $personaRepository;
        $this->authService = $authService; // Assign the service
    }

    public function index(): void
    {
        // AuthMiddleware::requireOnlySupervisor(); // Replaced by router middleware

        $ventas = $this->ventaRepository->obtenerTodos();
        $this->smarty->assign('ventas', $ventas);
        $this->smarty->assign('page_title', 'Gestión de Ventas');
        $this->smarty->display('ventas.tpl');
    }

    public function showFormCreate(): void
    {
        // AuthMiddleware::requireOnlySupervisor(); // Replaced by router middleware

        $repuestos = $this->repuestoRepository->obtenerTodos();
        $usuarios = [];

        if ($this->authService->isAdmin() || $this->authService->isSupervisor()) {
            $usuarios = $this->personaRepository->getAllUsers(); // Get all users for admin/supervisor
        } else {
            $usuario = $this->personaRepository->findById($this->authService->getUserId());
            if ($usuario) {
                $usuarios[] = $usuario;
            }
        }

        $this->smarty->assign('repuestos', $repuestos);
        $this->smarty->assign('usuarios', $usuarios);
        $this->smarty->assign('page_title', 'Añadir Venta');
        $this->smarty->assign('form_action', BASE_URL . 'ventas/create');
        $this->smarty->assign('is_edit', false);
        $this->smarty->assign('venta', null);
        $this->smarty->assign('form_data', []);
        $this->smarty->display('form_venta.tpl');
    }

    public function create(): void
    {
        // AuthMiddleware::requireOnlySupervisor(); // Replaced by router middleware

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new VentaValidator($this->repuestoRepository, $this->personaRepository);
            $data = [
                'repuesto_id' => $_POST['repuesto_id'] ?? null,
                'usuario_id' => $_POST['usuario_id'] ?? null,
                'cantidad' => $_POST['cantidad'] ?? null,
                'fecha' => $_POST['fecha'] ?? date('Y-m-d'),
            ];

            // If user role, set usuario_id from session
            if ($this->authService->isUser()) {
                $data['usuario_id'] = $this->authService->getUserId();
            }

            $repuesto = $this->repuestoRepository->obtenerPorId($data['repuesto_id']);
            $usuario = $this->personaRepository->findById($data['usuario_id']);
            $ventaWithSubmittedData = new Venta(null, $repuesto, $usuario, $data['cantidad'], $data['fecha']);

            if (!$validator->validate($data)) {
                $this->smarty->assign('error_message', implode(", ", $validator->getErrors()));
                $this->smarty->assign('form_data', $data);
                $this->smarty->assign('page_title', 'Añadir Venta');
                $this->smarty->assign('form_action', BASE_URL . 'ventas/create');
                $this->smarty->assign('is_edit', false);
                $this->smarty->assign('venta', $ventaWithSubmittedData);
                $this->smarty->assign('repuestos', $this->repuestoRepository->obtenerTodos());
                $this->smarty->assign('usuarios', ($this->authService->isAdmin() || $this->authService->isSupervisor()) ? $this->personaRepository->getAllUsers() : [$this->personaRepository->findById($this->authService->getUserId())]);
                $this->smarty->display('form_venta.tpl');
                return;
            }

            $newVenta = new Venta(null, $repuesto, $usuario, $data['cantidad'], $data['fecha']);

            if ($this->ventaRepository->guardar($newVenta)) {
                $this->redirect(BASE_URL . 'ventas');
            } else {
                $this->smarty->assign('error_message', 'Error al crear la venta.');
                $this->smarty->assign('form_data', $data);
                $this->smarty->assign('page_title', 'Añadir Venta');
                $this->smarty->assign('form_action', BASE_URL . 'ventas/create');
                $this->smarty->assign('is_edit', false);
                $this->smarty->assign('venta', $ventaWithSubmittedData);
                $this->smarty->assign('repuestos', $this->repuestoRepository->obtenerTodos());
                $this->smarty->assign('usuarios', ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'supervisor') ? $this->personaRepository->getAllUsers() : [$this->personaRepository->findById($_SESSION['user_id'])]);
                $this->smarty->display('form_venta.tpl');
            }
        }
    }

    public function showFormEdit(int $id): void
    {
        // AuthMiddleware::requireOnlySupervisor(); // Replaced by router middleware

        $venta = $this->ventaRepository->obtenerPorId($id);
        if (!$venta) {
            $this->redirect(BASE_URL . 'ventas');
            return;
        }

        // If user role, ensure they can only edit their own sales
        if ($this->authService->isUser() && $venta->getUsuario()->getId() !== $this->authService->getUserId()) {
            $this->redirect(BASE_URL . 'ventas');
            return;
        }

        $repuestos = $this->repuestoRepository->obtenerTodos();
        $usuarios = [];

        if ($this->authService->isAdmin() || $this->authService->isSupervisor()) {
            $usuarios = $this->personaRepository->getAllUsers();
        } else {
            $usuario = $this->personaRepository->findById($this->authService->getUserId());
            if ($usuario) {
                $usuarios[] = $usuario;
            }
        }

        $this->smarty->assign('repuestos', $repuestos);
        $this->smarty->assign('usuarios', $usuarios);
        $this->smarty->assign('page_title', 'Editar Venta');
        $this->smarty->assign('form_action', BASE_URL . 'ventas/update');
        $this->smarty->assign('is_edit', true);
        $this->smarty->assign('venta', $venta);
        $this->smarty->assign('form_data', []);
        $this->smarty->display('form_venta.tpl');
    }

    public function update(): void
    {
        // AuthMiddleware::requireOnlySupervisor(); // Replaced by router middleware

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new VentaValidator($this->repuestoRepository, $this->personaRepository);
            $data = [
                'id' => $_POST['id'] ?? null,
                'repuesto_id' => $_POST['repuesto_id'] ?? null,
                'usuario_id' => $_POST['usuario_id'] ?? null,
                'cantidad' => $_POST['cantidad'] ?? null,
                'fecha' => $_POST['fecha'] ?? date('Y-m-d'),
            ];

            $existingVenta = $this->ventaRepository->obtenerPorId($data['id']);
            if (!$existingVenta) {
                $this->redirect(BASE_URL . 'ventas');
                return;
            }

            // If user role, ensure they can only update their own sales
            if ($this->authService->isUser() && $existingVenta->getUsuario()->getId() !== $this->authService->getUserId()) {
                $this->redirect(BASE_URL . 'ventas');
                return;
            }

            // If user role, set usuario_id from session
            if ($this->authService->isUser()) {
                $data['usuario_id'] = $this->authService->getUserId();
            }

            $repuesto = $this->repuestoRepository->obtenerPorId($data['repuesto_id']);
            $usuario = $this->personaRepository->findById($data['usuario_id']);
            $ventaWithSubmittedData = new Venta($data['id'], $repuesto, $usuario, $data['cantidad'], $data['fecha']);

            if (!$validator->validate($data, true)) { // Pass true for isUpdate
                $this->smarty->assign('error_message', implode(", ", $validator->getErrors()));
                $this->smarty->assign('form_data', $data);
                $this->smarty->assign('page_title', 'Editar Venta');
                $this->smarty->assign('form_action', BASE_URL . 'ventas/update');
                $this->smarty->assign('is_edit', true);
                $this->smarty->assign('venta', $ventaWithSubmittedData);
                $this->smarty->assign('repuestos', $this->repuestoRepository->obtenerTodos());
                $this->smarty->assign('usuarios', ($this->authService->isAdmin() || $this->authService->isSupervisor()) ? $this->personaRepository->getAllUsers() : [$this->personaRepository->findById($this->authService->getUserId())]);
                $this->smarty->display('form_venta.tpl');
                return;
            }

            $ventaToUpdate = new Venta(
                $data['id'],
                $repuesto,
                $usuario,
                $data['cantidad'],
                $data['fecha']
            );

            if ($this->ventaRepository->guardar($ventaToUpdate)) {
                $this->redirect(BASE_URL . 'ventas');
            } else {
                $this->smarty->assign('error_message', 'Error al actualizar la venta.');
                $this->smarty->assign('form_data', $data);
                $this->smarty->assign('page_title', 'Editar Venta');
                $this->smarty->assign('form_action', BASE_URL . 'ventas/update');
                $this->smarty->assign('is_edit', true);
                $this->smarty->assign('venta', $ventaWithSubmittedData);
                $this->smarty->assign('repuestos', $this->repuestoRepository->obtenerTodos());
                $this->smarty->assign('usuarios', ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'supervisor') ? $this->personaRepository->getAllUsers() : [$this->personaRepository->findById($_SESSION['user_id'])]);
                $this->smarty->display('form_venta.tpl');
            }
        }
    }

    public function showConfirmDelete(int $id): void
    {
        // AuthMiddleware::requireOnlySupervisor(); // Replaced by router middleware

        $venta = $this->ventaRepository->obtenerPorId($id);
        if (!$venta) {
            $this->redirect(BASE_URL . 'ventas');
            return;
        }

        // If user role, ensure they can only delete their own sales
        if ($this->authService->isUser() && $venta->getUsuario()->getId() !== $this->authService->getUserId()) {
            $this->redirect(BASE_URL . 'ventas');
            return;
        }

        $this->smarty->assign('page_title', 'Confirmar Eliminación');
        $this->smarty->assign('venta', $venta);
        $this->smarty->display('confirm_delete_venta.tpl');
    }

    public function delete(int $id): void
    {
        // AuthMiddleware::requireOnlySupervisor(); // Replaced by router middleware

        $venta = $this->ventaRepository->obtenerPorId($id);
        if (!$venta) {
            $this->redirect(BASE_URL . 'ventas');
            return;
        }

        // If user role, ensure they can only delete their own sales
        if ($this->authService->isUser() && $venta->getUsuario()->getId() !== $this->authService->getUserId()) {
            $this->redirect(BASE_URL . 'ventas');
            return;
        }

        if ($this->ventaRepository->eliminar($id)) {
            $this->redirect(BASE_URL . 'ventas');
        } else {
            $this->redirect(BASE_URL . 'ventas');
        }
    }

    public function showDetail(int $id): void
    {
        // AuthMiddleware::requireOnlySupervisor(); // Replaced by router middleware

        $venta = $this->ventaRepository->obtenerPorId($id);
        if (!$venta || !($venta instanceof Venta)) {
            $this->redirect(BASE_URL . 'ventas');
            return;
        }

        // Authorization check: only admin/supervisor or the owner can view
        if ($this->authService->isUser() && $venta->getUsuario()->getId() !== $this->authService->getUserId()) {
            $this->redirect(BASE_URL . 'ventas'); // Unauthorized
            return;
        }

        $this->smarty->assign('page_title', 'Detalle de Venta');
        $this->smarty->assign('venta', $venta);
        $this->smarty->display('venta_detail.tpl');
    }
}