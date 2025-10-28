<?php
/**
 * Resetear la contraseña del usuario admin@alojatec.com a 'admin123'
 * Uso: php scripts/reset_admin_password.php
 */

$config = require __DIR__ . '/../config/database.php';

try {
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $config['host'], $config['database'], $config['charset'] ?? 'utf8mb4');
    $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options'] ?? []);
} catch (Exception $e) {
    echo "Error conectando a la base de datos: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

$email = 'admin@alojatec.com';
$newPassword = 'admin123';
$hashed = password_hash($newPassword, PASSWORD_DEFAULT);

$stmt = $pdo->prepare('UPDATE users SET password = ? WHERE email = ?');
$stmt->execute([$hashed, $email]);

if ($stmt->rowCount() > 0) {
    echo "Contraseña del usuario {$email} actualizada a: {$newPassword}\n";
} else {
    // Si no afectó filas, puede que el usuario no exista
    $check = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $check->execute([$email]);
    $exists = $check->fetchColumn();
    if ($exists) {
        echo "La contraseña parecía ya estar actualizada o no cambió (fila encontrada).\n";
    } else {
        echo "Usuario {$email} no encontrado en la base de datos.\n";
    }
}
