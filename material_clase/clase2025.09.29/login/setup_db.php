<?php

$databaseFile = __DIR__ . '/users.db';

try {
    $pdo = new PDO('sqlite:' . $databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $createTableSQL = "
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL
        );
    ";
    $pdo->exec($createTableSQL);
    echo "Database 'users.db' and table 'users' created successfully (or already exist).";
} catch (PDOException $e) {
    echo "Error setting up database: " . $e->getMessage();
}

?>