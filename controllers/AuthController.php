<?php
class AuthController {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function login(): void {
        header('Content-Type: application/json; charset=utf-8');
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            echo json_encode(['success' => false, 'message' => 'No se recibieron datos']);
            return;
        }

        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        $stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['password'])) {
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];

            $redirect = ($usuario['rol'] ?? '') === 'admin' ? '/admin' : null;
            echo json_encode([
                'success' => true,
                'message' => 'Login exitoso',
                'nombre' => $usuario['nombre'],
                'rol' => $usuario['rol'],
                'redirect' => $redirect,
            ]);
            return;
        }

        echo json_encode(['success' => false, 'message' => 'Correo o contraseña incorrectos.']);
    }

    public function register(): void {
        header('Content-Type: application/json; charset=utf-8');
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            echo json_encode(['success' => false, 'message' => 'No se recibieron datos']);
            return;
        }

        $nombre = trim($data['nombre'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        if ($nombre === '' || $email === '' || $password === '') {
            echo json_encode(['success' => false, 'message' => 'Rellena todos los campos.']);
            return;
        }

        try {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare('INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, "cliente")');
            $stmt->execute([':nombre' => $nombre, ':email' => $email, ':password' => $passwordHash]);

            $_SESSION['usuario_id'] = $this->pdo->lastInsertId();
            $_SESSION['usuario_nombre'] = $nombre;
            $_SESSION['rol'] = 'cliente';

            echo json_encode([
                'success' => true,
                'message' => 'Registro exitoso',
                'nombre' => $nombre,
                'rol' => 'cliente',
                'redirect' => null,
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                echo json_encode(['success' => false, 'message' => 'Este correo ya está registrado. Prueba a iniciar sesión.']);
                return;
            }
            echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
        }
    }

    public function logout(): void {
        session_destroy();
        header('Location: /');
        exit;
    }
}
