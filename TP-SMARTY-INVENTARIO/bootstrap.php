<?php

// Load environment variables
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Use statements for namespaced classes
use App\Database\DBConnection;
use App\Controladores\LoginController;
use App\Controladores\RegisterController;
use App\Repositories\PersonaRepository;
use App\Controladores\UsuarioController;
use App\Controladores\RepuestoController;
use App\Controladores\VentaController;
use App\Controladores\CartController;
use App\Repositories\RepuestoRepository;
use App\Repositories\VentaRepository;
use App\Repositories\PedidoRepository;
use App\Repositories\DetallePedidoRepository;
use App\Validators\VentaValidator;

try {
    // Initialize Smarty
    $smarty = new Smarty();
    $smarty->setTemplateDir(__DIR__ . '/templates');
    $smarty->setCompileDir(__DIR__ . '/templates_c');
    $smarty->setCacheDir(__DIR__ . '/cache');
    $smarty->setCaching($_ENV['SMARTY_CACHE_ENABLED'] === 'true' ? Smarty::CACHING_LIFETIME_CURRENT : Smarty::CACHING_OFF);

    // Define BASE_URL and SERVER_PATH
    $script_name = $_SERVER['SCRIPT_NAME'];
    $base_path = str_replace(basename($script_name), '', $script_name);
    define('BASE_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $base_path);
    define('SERVER_PATH', $_SERVER['DOCUMENT_ROOT'] . $base_path);

    if ($_ENV['APP_DEBUG'] === 'true') {
        error_log("BASE_URL: " . BASE_URL);
    }

    $smarty->assign('BASE_URL', BASE_URL);
    $smarty->assign('SERVER_PATH', SERVER_PATH);

    // Get PDO connection
    $db = DBConnection::getInstance();
    $pdo = $db->getConnection();

    // Instantiate repositories
    $personaRepository = new PersonaRepository($pdo);
    $repuestoRepository = new RepuestoRepository($pdo);
    $ventaRepository = new VentaRepository($pdo);
    $pedidoRepository = new PedidoRepository($pdo, $repuestoRepository, $personaRepository);
    $detallePedidoRepository = new DetallePedidoRepository($pdo);

    // Instantiate validators
    $ventaValidator = new VentaValidator($repuestoRepository, $personaRepository);

    // Instantiate controllers
    $loginController = new LoginController($smarty, $personaRepository);
    $registerController = new RegisterController($smarty, $personaRepository);
    $usuarioController = new UsuarioController($smarty, $personaRepository);
    $repuestoController = new RepuestoController($smarty, $repuestoRepository);
    $ventaController = new VentaController($smarty, $ventaRepository, $repuestoRepository, $personaRepository);
    $cartController = new CartController($smarty, $repuestoRepository, $pedidoRepository, $personaRepository);

    // Return container with instantiated objects
    return [
        'smarty' => $smarty,
        'pdo' => $pdo,
        'personaRepository' => $personaRepository,
        'repuestoRepository' => $repuestoRepository,
        'ventaRepository' => $ventaRepository,
        'pedidoRepository' => $pedidoRepository,
        'detallePedidoRepository' => $detallePedidoRepository,
        'ventaValidator' => $ventaValidator,
        'loginController' => $loginController,
        'registerController' => $registerController,
        'usuarioController' => $usuarioController,
        'repuestoController' => $repuestoController,
        'ventaController' => $ventaController,
        'cartController' => $cartController,
    ];
} catch (Exception $e) {
    error_log("Bootstrap Error: " . $e->getMessage());
    if ($_ENV['APP_DEBUG'] === 'true') {
        die("Error al inicializar la aplicación: " . $e->getMessage());
    } else {
        die("Error al inicializar la aplicación. Por favor, contacte al administrador.");
    }
}
