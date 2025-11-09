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
    private AuthService $authService; // Add this property

    public function __construct(Smarty $smarty, PedidoRepository $pedidoRepository, PersonaRepository $personaRepository, AuthService $authService)
    {
        parent::__construct($smarty);
        $this->pedidoRepository = $pedidoRepository;
        $this->personaRepository = $personaRepository;
        $this->authService = $authService; // Assign the service
    }

    public function index(): void
    {
        // AuthMiddleware::requireLogin(); // Replaced by router middleware

        $pedidos = [];
        if ($this->authService->isAdmin() || $this->authService->isSupervisor()) {
            $pedidos = $this->pedidoRepository->obtenerTodos();
        } elseif ($this->authService->isUser()) {
            $pedidos = $this->pedidoRepository->obtenerPedidosPorUsuarioId($this->authService->getUserId());
        }

        $this->smarty->assign('pedidos', $pedidos);
        $this->smarty->assign('page_title', 'Gestión de Pedidos');
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

            // Users can only update their own orders
            if ($this->authService->isUser() && $pedido->getUsuarioId() !== $this->authService->getUserId()) {
                $_SESSION['error_message'] = 'No tienes permiso para actualizar este pedido.';
                $this->redirect(BASE_URL . 'pedidos');
                return;
            }

            // Validate new state based on role
            if ($this->authService->isUser()) {
                // Users can only cancel their own orders
                if ($estado !== 'cancelado' || $pedido->getEstado() !== 'pendiente') {
                    $_SESSION['error_message'] = 'Solo puedes cancelar pedidos pendientes.';
                    $this->redirect(BASE_URL . 'pedidos');
                    return;
                }
            } elseif (!in_array($estado, ['pendiente', 'procesado', 'enviado', 'entregado', 'cancelado'])) {
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
