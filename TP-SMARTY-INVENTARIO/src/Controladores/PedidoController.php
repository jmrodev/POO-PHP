<?php

namespace App\Controladores;

use App\Modelos\Pedido;
use App\Modelos\Usuario;
use App\Repositories\PedidoRepository;
use App\Repositories\PersonaRepository;
use Smarty;

use App\Services\AuthService; // Add this use statement

class PedidoController extends BaseController
{
    private PedidoRepository $pedidoRepository;
    private PersonaRepository $personaRepository;

    public function __construct(Smarty $smarty, PedidoRepository $pedidoRepository, PersonaRepository $personaRepository, AuthService $authService)
    {
        parent::__construct($smarty, $authService);
        $this->pedidoRepository = $pedidoRepository;
        $this->personaRepository = $personaRepository;
    }

    public function index(): void
    {
        // AuthMiddleware::requireLogin(); // Replaced by router middleware

        $perPage = 10; // Número de pedidos por página
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($currentPage < 1) {
            $currentPage = 1;
        }

        $pedidos = [];
        $totalPedidos = 0;
        $baseURL = BASE_URL . 'pedidos';

        if ($this->authService->isAdmin() || $this->authService->isSupervisor()) {
            $pedidos = $this->pedidoRepository->obtenerPaginado($currentPage, $perPage);
            $totalPedidos = $this->pedidoRepository->contarTodos();
        } elseif ($this->authService->isUser()) {
            $userId = $this->authService->getUserId();
            $pedidos = $this->pedidoRepository->obtenerPedidosPorUsuarioIdPaginado($userId, $currentPage, $perPage);
            $totalPedidos = $this->pedidoRepository->contarPedidosPorUsuarioId($userId);
        }

        $totalPages = ceil($totalPedidos / $perPage);

        $this->smarty->assign('pedidos', $pedidos);
        $this->smarty->assign('page_title', 'Gestión de Pedidos');
        $this->smarty->assign('currentPage', $currentPage);
        $this->smarty->assign('totalPages', $totalPages);
        $this->smarty->assign('perPage', $perPage);
        $this->smarty->assign('baseURL', $baseURL); // Base URL for pagination links
        $this->smarty->display('pedidos.tpl');
    }

    public function showDetail(int $id): void
    {
        // AuthMiddleware::requireLogin(); // Replaced by router middleware

        $pedido = $this->pedidoRepository->obtenerPorId($id);

        if (!$pedido) {
            $_SESSION['error_message'] = 'Pedido no encontrado.';
            $this->redirect(BASE_URL . 'pedidos');
            return;
        }

        // Only allow users to view their own orders, unless admin/supervisor
        if ($this->authService->isUser() && $pedido->getUsuarioId() !== $this->authService->getUserId()) {
            $_SESSION['error_message'] = 'No tienes permiso para ver este pedido.';
            $this->redirect(BASE_URL . 'pedidos');
            return;
        }

        $this->smarty->assign('pedido', $pedido);
        $this->smarty->assign('page_title', 'Detalle del Pedido');
        $this->smarty->display('pedido_detail.tpl');
    }

    public function showFormEdit(int $id): void
    {
        // AuthMiddleware::requireLogin(); // Replaced by router middleware

        // Prevent client users from accessing the edit form
        if ($this->authService->isUser()) {
            $_SESSION['error_message'] = 'No tienes permiso para editar pedidos.';
            $this->redirect(BASE_URL . 'pedidos');
            return;
        }

        $pedido = $this->pedidoRepository->obtenerPorId($id);

        if (!$pedido) {
            $_SESSION['error_message'] = 'Pedido no encontrado.';
            $this->redirect(BASE_URL . 'pedidos');
            return;
        }

        // Admins can edit any order, users can only edit their own
        if ($this->authService->isUser() && $pedido->getUsuarioId() !== $this->authService->getUserId()) {
            $_SESSION['error_message'] = 'No tienes permiso para editar este pedido.';
            $this->redirect(BASE_URL . 'pedidos');
            return;
        }

        $this->smarty->assign('pedido', $pedido);
        $this->smarty->assign('form_action', BASE_URL . 'pedidos/update');
        $this->smarty->assign('page_title', 'Editar Pedido');
        $this->smarty->display('form_pedido.tpl');
    }

    public function update(): void
    {
        // AuthMiddleware::requireLogin(); // Replaced by router middleware

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $estado = $_POST['estado'] ?? null;

            if (!$id || !$estado) {
                $_SESSION['error_message'] = 'Datos de actualización incompletos.';
                $this->redirect(BASE_URL . 'pedidos');
                return;
            }

            $pedido = $this->pedidoRepository->obtenerPorId($id);
            if (!$pedido) {
                $_SESSION['error_message'] = 'Pedido no encontrado.';
                $this->redirect(BASE_URL . 'pedidos');
                return;
            }

            // Prevent client users from updating any part of the order
            if ($this->authService->isUser()) {
                $_SESSION['error_message'] = 'No tienes permiso para actualizar pedidos.';
                $this->redirect(BASE_URL . 'pedidos');
                return;
            }

            // Validate new state based on role (only for admin/supervisor)
            if (!in_array($estado, ['pendiente', 'completado', 'cancelado'])) {
                $_SESSION['error_message'] = 'Estado de pedido inválido.';
                $this->redirect(BASE_URL . 'pedidos');
                return;
            }

            $pedido->setEstado($estado);
            if ($this->pedidoRepository->guardar($pedido)) {
                $_SESSION['success_message'] = 'Pedido actualizado con éxito.';
            } else {
                $_SESSION['error_message'] = 'Error al actualizar el pedido.';
            }
        }
        $this->redirect(BASE_URL . 'pedidos');
    }

    public function showConfirmDelete(int $id): void
    {
        // AuthMiddleware::requireLogin(); // Replaced by router middleware

        $pedido = $this->pedidoRepository->obtenerPorId($id);

        if (!$pedido) {
            $_SESSION['error_message'] = 'Pedido no encontrado.';
            $this->redirect(BASE_URL . 'pedidos');
            return;
        }

        // Only allow users to delete their own orders, unless admin/supervisor
        if ($this->authService->isUser() && $pedido->getUsuarioId() !== $this->authService->getUserId()) {
            $_SESSION['error_message'] = 'No tienes permiso para cancelar este pedido.';
            $this->redirect(BASE_URL . 'pedidos');
            return;
        }

        $this->smarty->assign('pedido', $pedido);
        $this->smarty->assign('page_title', 'Confirmar Cancelación de Pedido');
        $this->smarty->display('confirm_delete_pedido.tpl');
    }

    public function delete(int $id): void
    {
        // AuthMiddleware::requireLogin(); // Replaced by router middleware

        if ($this->authService->isAdmin()) {
            $this->redirect(BASE_URL . 'home'); // Admins should not access order management
            return;
        }

        $pedido = $this->pedidoRepository->obtenerPorId($id);

        if (!$pedido) {
            $_SESSION['error_message'] = 'Pedido no encontrado.';
            $this->redirect(BASE_URL . 'pedidos');
            return;
        }

        // Only allow users to delete their own orders, unless admin/supervisor
        if ($this->authService->isUser() && $pedido->getUsuarioId() !== $this->authService->getUserId()) {
            $_SESSION['error_message'] = 'No tienes permiso para cancelar este pedido.';
            $this->redirect(BASE_URL . 'pedidos');
            return;
        }

        // For users, only allow cancellation if status is 'pendiente'
        if ($this->authService->isUser() && $pedido->getEstado() !== 'pendiente') {
            $_SESSION['error_message'] = 'Solo puedes cancelar pedidos pendientes.';
            $this->redirect(BASE_URL . 'pedidos');
            return;
        }

        if ($this->pedidoRepository->eliminar($id)) {
            $_SESSION['success_message'] = 'Pedido cancelado con éxito.';
            $this->redirect(BASE_URL . 'pedidos');
        } else {
            $_SESSION['error_message'] = 'Error al cancelar el pedido.';
            $this->redirect(BASE_URL . 'pedidos');
        }
    }
}
