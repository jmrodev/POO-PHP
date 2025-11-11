<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Debugging: Check if environment variables are loaded
// var_dump($_ENV); exit;

$host = $_ENV['DB_HOST'];
$db   = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASSWORD'];
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$stmt = $pdo->prepare("INSERT INTO repuestos (nombre, precio, cantidad, imagen) VALUES (:nombre, :precio, :cantidad, NULL)");

for ($i = 1; $i <= 20; $i++) { // Insert 20 additional repuestos
    $nombre = "Repuesto de Prueba " . $i;
    $precio = round(rand(1000, 10000) / 100, 2); // Random price between 10.00 and 100.00
    $cantidad = rand(1, 50); // Random quantity between 1 and 50

    $stmt->execute([
        ':nombre' => $nombre,
        ':precio' => $precio,
        ':cantidad' => $cantidad
    ]);
}

echo "Se han insertado 20 repuestos de prueba en la base de datos.\n";
?>
