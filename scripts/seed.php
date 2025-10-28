<?php
/**
 * Script de semillas (seed) para crear usuarios de prueba.
 * Ejecutar desde la línea de comandos:
 * php scripts/seed.php
 */

require_once __DIR__ . '/../config/database.php';

$dbConfig = require __DIR__ . '/../config/database.php';

try {
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $dbConfig['host'], $dbConfig['database'], $dbConfig['charset'] ?? 'utf8mb4');
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $dbConfig['options'] ?? []);
} catch (Exception $e) {
    echo "Error conectando a la base de datos: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

// Usuarios de prueba a insertar
$testUsers = [
    [
        'name' => 'Comprador',
        'email' => 'comprador@alojatec.com',
        'password' => 'comprador123',
        'role' => 'user',
    ],
    [
        'name' => 'UsuarioPrueba',
        'email' => 'usuario@alojatec.com',
        'password' => 'usuario123',
        'role' => 'user',
    ],
];

foreach ($testUsers as $user) {
    // Verificar si existe
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$user['email']]);
    $exists = $stmt->fetchColumn();

    if ($exists) {
        echo "Usuario ya existe: {$user['email']}\n";
        continue;
    }

    $hashed = password_hash($user['password'], PASSWORD_DEFAULT);
    $insert = $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
    $insert->execute([$user['name'], $user['email'], $hashed, $user['role']]);
    echo "Usuario creado: {$user['email']} (password: {$user['password']})\n";
}

echo "Seed finalizado.\n";
