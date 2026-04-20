<?php
// Datos de conexión (coinciden con vuestro docker-compose.yml)
$host = getenv('DB_HOST') ?: 'db'; // Nombre del servicio en Docker
$db   = getenv('DB_NAME') ?: 'beatdrop_db';
$user = getenv('DB_USER') ?: 'beatdrop_user';
$pass = getenv('DB_PASS') ?: 'password';
$charset = getenv('DB_CHARSET') ?: 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $attempts = 12;
    $delayMs = 500;

    for ($i = 1; $i <= $attempts; $i++) {
        try {
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            break;
        } catch (\PDOException $e) {
            if ($i === $attempts) {
                throw $e;
            }
            usleep($delayMs * 1000);
        }
    }
} catch (\PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
