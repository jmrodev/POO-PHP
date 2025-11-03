<?php

$config = [
    'servername' => 'localhost',
    'username' => 'root',
    'password' => 'jmro1975',
    'dbname' => 'inventarioRepuestos'
];

try {
    $pdo = new PDO("mysql:host={$config['servername']};dbname={$config['dbname']};charset=utf8mb4", $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $createTableSQL = "
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL
        );
    ";
    $pdo->exec($createTableSQL);
    echo "Database 'users' table created successfully (or already exist).\n";

    // Add a default user for testing
    $username = 'luis';
    $password = password_hash('1234', PASSWORD_DEFAULT); // Hash the password

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        echo "Default user added.\n";
    } else {
        echo "Default user already exists.\n";
    }

} catch (PDOException $e) {
    die("Error setting up database: " . $e->getMessage());
}

?>
