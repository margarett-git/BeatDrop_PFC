<?php
// Datos de conexión (coinciden con vuestro docker-compose.yml)
$host = getenv('DB_HOST') ?: 'db'; // Nombre del servicio en Docker
$db   = getenv('DB_NAME') ?: 'beatdrop_db';
$user = getenv('DB_USER') ?: 'beatdrop_user';
$pass = getenv('DB_PASS') ?: 'password';
$charset = getenv('DB_CHARSET') ?: 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

function sincronizarAdmin(PDO $pdo): void {
    $adminEmail = getenv('ADMIN_EMAIL') ?: 'beatdroptfg@gmail.com';
    $adminPassword = getenv('ADMIN_PASSWORD') ?: 'admin123';
    $adminName = getenv('ADMIN_NAME') ?: 'Admin BeatDrop';

    try {
        $tableCheck = $pdo->query("SHOW TABLES LIKE 'usuarios'");
        if (!$tableCheck || !$tableCheck->fetchColumn()) {
            return;
        }

        $stmt = $pdo->prepare('SELECT id_usuario, nombre, password, rol FROM usuarios WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $adminEmail]);
        $admin = $stmt->fetch();

        $requiereActualizacion = !$admin
            || ($admin['rol'] ?? '') !== 'admin'
            || ($admin['nombre'] ?? '') !== $adminName
            || !password_verify($adminPassword, $admin['password'] ?? '');

        if (!$requiereActualizacion) {
            return;
        }

        $passwordHash = password_hash($adminPassword, PASSWORD_DEFAULT);

        if ($admin) {
            $update = $pdo->prepare(
                'UPDATE usuarios
                 SET nombre = :nombre, password = :password, rol = "admin"
                 WHERE id_usuario = :id'
            );
            $update->execute([
                ':nombre' => $adminName,
                ':password' => $passwordHash,
                ':id' => $admin['id_usuario'],
            ]);
            return;
        }

        $insert = $pdo->prepare(
            'INSERT INTO usuarios (nombre, email, password, rol)
             VALUES (:nombre, :email, :password, "admin")'
        );
        $insert->execute([
            ':nombre' => $adminName,
            ':email' => $adminEmail,
            ':password' => $passwordHash,
        ]);
    } catch (\PDOException $e) {
        // Si la tabla todavía no existe o está en migración, no bloqueamos la app.
    }
}

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
            sincronizarAdmin($pdo);
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
