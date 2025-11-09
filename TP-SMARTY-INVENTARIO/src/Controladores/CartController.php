<?php

namespace App\Controladores;

use App\Repositories\RepuestoRepository;
use App\Repositories\PedidoRepository;
use App\Repositories\PersonaRepository;
use App\Modelos\Pedido;
use App\Modelos\DetallePedido;
use Smarty;

class CartController extends BaseController
{
    private RepuestoRepository $repuestoRepository;
    private PedidoRepository $pedidoRepository;
    private PersonaRepository $personaRepository;

    public function __construct(Smarty $smarty, RepuestoRepository $repuestoRepository, PedidoRepository $pedidoRepository, PersonaRepository $personaRepository)
    {
        parent::__construct($smarty);
        $this->repuestoRepository = $repuestoRepository;
        $this->pedidoRepository = $pedidoRepository;
        $this->personaRepository = $personaRepository;

        // Initialize cart in session if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function showCatalog(): void
    {
        AuthMiddleware::requireUserOnly();

        $repuestos = $this->repuestoRepository->obtenerTodos();
        $this->smarty->assign('repuestos', $repuestos);
        $this->smarty->assign('page_title', 'Catálogo de Repuestos');
        $this->smarty->assign('cart_items', $_SESSION['cart']); // Pass cart items to display count
        $this->smarty->display('product_catalog.tpl');
    }

    public function addToCart(): void
    {
        AuthMiddleware::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $repuestoId = $_POST['repuesto_id'] ?? null;
            $cantidad = $_POST['cantidad'] ?? 1;

            if ($repuestoId === null || !is_numeric($cantidad) || $cantidad <= 0) {
                // Handle error: invalid input
                $this->redirect(BASE_URL . 'catalog');
                return;
            }

            $repuesto = $this->repuestoRepository->obtenerPorId((int)$repuestoId);

            if (!$repuesto) {
                // Handle error: repuesto not found
                $this->redirect(BASE_URL . 'catalog');
                return;
            }

            // Check if enough stock is available
            if ($repuesto->getCantidad() < $cantidad) {
                // Handle error: not enough stock
                $_SESSION['error_message'] = 'No hay suficiente stock para ' . $repuesto->getNombre();
                $this->redirect(BASE_URL . 'catalog');
                return;
            }

            // Add to cart or update quantity if already in cart
            if (isset($_SESSION['cart'][$repuestoId])) {
                $_SESSION['cart'][$repuestoId]['cantidad'] += $cantidad;
            } else {
                $_SESSION['cart'][$repuestoId] = [
                    'id' => $repuesto->getId(),
                    'nombre' => $repuesto->getNombre(),
                    'precio' => $repuesto->getPrecio(),
                    'cantidad' => $cantidad,
                    'imagen' => $repuesto->getImagen() // Include image for display in cart
                ];
            }
            $_SESSION['success_message'] = $cantidad . ' unidades de ' . $repuesto->getNombre() . ' añadidas al carrito.';
        }
        $this->redirect(BASE_URL . 'catalog');
    }

    public function showCart(): void
    {
        AuthMiddleware::requireLogin();

        $cartItems = $_SESSION['cart'];
        $cartTotal = 0;
        foreach ($cartItems as $item) {
            $cartTotal += $item['precio'] * $item['cantidad'];
        }

        $this->smarty->assign('cart_items', $cartItems);
        $this->smarty->assign('cart_total', $cartTotal);
        $this->smarty->assign('page_title', 'Tu Carrito de Compras');
        $this->smarty->display('cart.tpl');
    }

    public function updateCartItem(): void
    {
        AuthMiddleware::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $repuestoId = $_POST['repuesto_id'] ?? null;
            $newCantidad = $_POST['cantidad'] ?? null;

            if ($repuestoId === null || !is_numeric($newCantidad) || $newCantidad < 0) {
                // Handle error: invalid input
                $this->redirect(BASE_URL . 'cart');
                return;
            }

            if (isset($_SESSION['cart'][$repuestoId])) {
                if ($newCantidad == 0) {
                    unset($_SESSION['cart'][$repuestoId]); // Remove item if quantity is 0
                    $_SESSION['success_message'] = 'Producto eliminado del carrito.';
                } else {
                    $repuesto = $this->repuestoRepository->obtenerPorId((int)$repuestoId);
                    if (!$repuesto || $repuesto->getCantidad() < $newCantidad) {
                        $_SESSION['error_message'] = 'No hay suficiente stock para ' . $_SESSION['cart'][$repuestoId]['nombre'];
                    } else {
                        $_SESSION['cart'][$repuestoId]['cantidad'] = $newCantidad;
                        $_SESSION['success_message'] = 'Cantidad actualizada.';
                    }
                }
            }
        }
        $this->redirect(BASE_URL . 'cart');
    }

    public function removeFromCart(int $repuestoId): void
    {
        AuthMiddleware::requireLogin();

        if (isset($_SESSION['cart'][$repuestoId])) {
            unset($_SESSION['cart'][$repuestoId]);
            $_SESSION['success_message'] = 'Producto eliminado del carrito.';
        }
        $this->redirect(BASE_URL . 'cart');
    }

    public function checkout(): void
    {
        AuthMiddleware::requireLogin();

        if (empty($_SESSION['cart'])) {
            $_SESSION['error_message'] = 'El carrito está vacío.';
            $this->redirect(BASE_URL . 'cart');
            return;
        }

        $userId = $_SESSION['user_id'];
        $cartItems = $_SESSION['cart'];
        $totalPedido = 0;
        $detallesPedido = [];

        // Validate stock and calculate total
        foreach ($cartItems as $item) {
            $repuesto = $this->repuestoRepository->obtenerPorId($item['id']);
            if (!$repuesto || $repuesto->getCantidad() < $item['cantidad']) {
                $_SESSION['error_message'] = 'No hay suficiente stock para ' . $item['nombre'] . '. Por favor, ajusta la cantidad.';
                $this->redirect(BASE_URL . 'cart');
                return;
            }
            $totalPedido += $item['precio'] * $item['cantidad'];
            $detallesPedido[] = new DetallePedido(null, 0, $item['id'], $item['cantidad'], $item['precio']);
        }

        // Create Pedido object
        $pedido = new Pedido(null, $userId, date('Y-m-d H:i:s'), $totalPedido, 'pendiente');
        $pedido->setDetalles($detallesPedido);

        // Save Pedido and DetallePedido
        if ($this->pedidoRepository->guardar($pedido)) {
            // Update repuesto stock
            foreach ($detallesPedido as $detalle) {
                $repuesto = $this->repuestoRepository->obtenerPorId($detalle->getRepuestoId());
                if ($repuesto) {
                    $repuesto->setCantidad($repuesto->getCantidad() - $detalle->getCantidad());
                    $this->repuestoRepository->guardar($repuesto); // Assuming guardar also handles updates
                }
            }

            $_SESSION['cart'] = []; // Clear cart
            $_SESSION['success_message'] = 'Pedido realizado con éxito. ID de Pedido: ' . $pedido->getId();
            $this->redirect(BASE_URL . 'pedidos'); // Redirect to orders list
        } else {
            $_SESSION['error_message'] = 'Error al procesar el pedido.';
            $this->redirect(BASE_URL . 'cart');
        }
    }
}
