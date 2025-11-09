<?php

namespace App\Controladores;

use App\Modelos\Pedido;
use App\Modelos\Usuario;
use App\Repositories\PedidoRepository;
use App\Repositories\PersonaRepository;
use Smarty;

class PedidoController extends BaseController
{
    private PedidoRepository $pedidoRepository;
    private PersonaRepository $personaRepository;

    public function __construct(Smarty $smarty, PedidoRepository $pedidoRepository, PersonaRepository $personaRepository)
    {
        parent::__construct($smarty);
        $this->pedidoRepository = $pedidoRepository;
        $this->personaRepository = $personaRepository;
    }

    public function index(): void
    {
        AuthMiddleware::requireLogin();

        $pedidos = [];
        if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'supervisor') {
            $pedidos = $this->pedidoRepository->obtenerTodos();
        } elseif ($_SESSION['role'] === 'user') {
            $pedidos = $this->pedidoRepository->obtenerPedidosPorUsuarioId($_SESSION['user_id']);
        }

        $this->smarty->assign('pedidos', $pedidos);
        $this->smarty->assign('page_title', 'Gestión de Pedidos');
        $this->smarty->display('pedidos.tpl');
    }

    public function showDetail(int $id): void
    {
        AuthMiddleware::requireLogin();

        $pedido = $this->pedidoRepository->obtenerPorId($id);

        if (!$pedido) {
            $this->redirect(BASE_URL . 'pedidos');
            return;
        }

        // Authorization check: only admin/supervisor or the owner can view
        if ($_SESSION['role'] === 'user' && $pedido->getUsuarioId() !== $_SESSION['user_id']) {
            $this->redirect(BASE_URL . 'pedidos'); // Unauthorized
            return;
        }

        $this->smarty->assign('pedido', $pedido);
        $this->smarty->assign('page_title', 'Detalle del Pedido');
        $this->smarty->display('pedido_detail.tpl');
    }

    public function showFormEdit(int $id): void
    {
        AuthMiddleware::requireLogin();

        $pedido = $this->pedidoRepository->obtenerPorId($id);

        if (!$pedido) {
            $this->redirect(BASE_URL . 'pedidos');
            return;
        }

        // Authorization check: only admin/supervisor or the owner can edit
        if ($_SESSION['role'] === 'user' && $pedido->getUsuarioId() !== $_SESSION['user_id']) {
            $this->redirect(BASE_URL . 'pedidos'); // Unauthorized
            return;
        }

        $this->smarty->assign('pedido', $pedido);
        $this->smarty->assign('page_title', 'Editar Pedido');
        $this->smarty->assign('form_action', BASE_URL . 'pedidos/update');
        $this->smarty->assign('is_edit', true);
        $this->smarty->display('form_pedido.tpl');
    }

    public function update(): void
    {
        AuthMiddleware::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $estado = $_POST['estado'] ?? null;

            if (!$id || !$estado) {
                $_SESSION['error_message'] = 'Datos de actualización incompletos.';
                $this->redirect(BASE_URL . 'pedidos');
                return;
            }

            $pedido = $this->pedidoRepository->obtenerPorId((int)$id);

            if (!$pedido) {
                $_SESSION['error_message'] = 'Pedido no encontrado.';
                $this->redirect(BASE_URL . 'pedidos');
                return;
            }

            // Authorization check: only admin/supervisor or the owner can update
            if ($_SESSION['role'] === 'user' && $pedido->getUsuarioId() !== $_SESSION['user_id']) {
                $this->redirect(BASE_URL . 'pedidos'); // Unauthorized
                return;
            }

            // Validate new state based on role
            $allowedStates = ['pendiente', 'completado', 'cancelado'];
            if ($_SESSION['role'] === 'user') {
                // Users can only cancel their own orders
                $allowedStates = ['cancelado'];
                if ($pedido->getEstado() !== 'pendiente' || $estado !== 'cancelado') {
                    $_SESSION['error_message'] = 'Solo puedes cancelar pedidos pendientes.';
                    $this->redirect(BASE_URL . 'pedidos/edit/' . $id);
                    return;
                }
            }

            if (!in_array($estado, $allowedStates)) {
                $_SESSION['error_message'] = 'Estado de pedido inválido.';
                $this->redirect(BASE_URL . 'pedidos/edit/' . $id);
                return;
            }

            $pedido->setEstado($estado);

            if ($this->pedidoRepository->guardar($pedido)) {
                $_SESSION['success_message'] = 'Pedido actualizado con éxito.';
                $this->redirect(BASE_URL . 'pedidos');
            } else {
                $_SESSION['error_message'] = 'Error al actualizar el pedido.';
                $this->redirect(BASE_URL . 'pedidos/edit/' . $id);
            }
        }
    }

    public function showConfirmDelete(int $id): void
    {
        AuthMiddleware::requireLogin();

        $pedido = $this->pedidoRepository->obtenerPorId($id);

        if (!$pedido) {
            $this->redirect(BASE_URL . 'pedidos');
            return;
        }

        // Authorization check: only admin/supervisor or the owner can delete/cancel
        if ($_SESSION['role'] === 'user' && $pedido->getUsuarioId() !== $_SESSION['user_id']) {
            $this->redirect(BASE_URL . 'pedidos'); // Unauthorized
            return;
        }

        $this->smarty->assign('pedido', $pedido);
        $this->smarty->assign('page_title', 'Confirmar Cancelación de Pedido');
        $this->smarty->display('confirm_delete_pedido.tpl');
    }

    public function delete(int $id): void
    {
        AuthMiddleware::requireLogin();

        $pedido = $this->pedidoRepository->obtenerPorId($id);

        if (!$pedido) {
            $this->redirect(BASE_URL . 'pedidos');
            return;
        }

        // Authorization check: only admin/supervisor or the owner can delete/cancel
        if ($_SESSION['role'] === 'user' && $pedido->getUsuarioId() !== $_SESSION['user_id']) {
            $this->redirect(BASE_URL . 'pedidos'); // Unauthorized
            return;
        }

        // For users, only allow cancellation if status is 'pendiente'
        if ($_SESSION['role'] === 'user' && $pedido->getEstado() !== 'pendiente') {
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
