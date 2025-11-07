<?php

$config = [
    'servername' => '127.0.0.1',
    'username' => 'root',
    'password' => 'jmro1975',
    'dbname' => 'inventarioRepuestos',
];

try {
    $pdo = new PDO("mysql:host={$config['servername']};dbname={$config['dbname']};charset=utf8mb4", $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = file_get_contents(__DIR__ . '/db/inventario_db.sql');
    $pdo->exec($sql);
    echo "Database schema from inventario_db.sql applied successfully.\n";

    $personaRepository = new App\Repositories\PersonaRepository($pdo);

    $username = 'admin';
    $password = password_hash('Jmro1975', PASSWORD_DEFAULT);
    $nombre = 'Administrador Principal';

    if (!$personaRepository->findByUsername($username)) {
        $admin = new App\Modelos\Administrador(null, $nombre, $username, $password);
        $personaRepository->save($admin);
        echo "Default admin user added.\n";
    } else {
        echo "Default admin user already exists.\n";
    }

} catch (PDOException $e) {
    die("Error setting up database: " . $e->getMessage());
}
