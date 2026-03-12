<?php
// Datos de conexión (coinciden con vuestro docker-compose.yml)
$host = 'db'; // Nombre del servicio en Docker
$db   = 'beatdrop_db';
$user = 'beatdrop_user';
$pass = 'password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    // Usamos PDO para mayor seguridad y flexibilidad en DAW [cite: 17, 28]
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (\PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}