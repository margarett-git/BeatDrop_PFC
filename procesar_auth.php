<?php
// Arrancamos la sesión para poder recordar al usuario en todas las páginas
session_start();

// Conectamos a la base de datos
require_once 'config/db.php';

// Leemos los datos que nos enviará JavaScript (Fetch API) en formato JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No se recibieron datos']);
    exit;
}

$email = $data['email'] ?? '';
$password = $data['password'] ?? '';
$nombre = $data['nombre'] ?? '';
$accion = $data['accion'] ?? 'login'; // Puede ser 'login' o 'registro'

if ($accion === 'registro') {
    // ==========================================
    // LÓGICA DE REGISTRO
    // ==========================================
    try {
        // ¡LA CLAVE DE LA SEGURIDAD! Encriptamos la contraseña
        $password_encriptada = password_hash($password, PASSWORD_DEFAULT);
        
        // Preparamos la consulta para evitar inyecciones SQL
        $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, 'cliente')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':password' => $password_encriptada
        ]);
        
        // Si todo va bien, iniciamos sesión automáticamente al nuevo usuario
        $_SESSION['usuario_id'] = $pdo->lastInsertId();
        $_SESSION['usuario_nombre'] = $nombre;
        $_SESSION['rol'] = 'cliente';
        
        // Le respondemos a JavaScript que todo ha ido genial
        echo json_encode(['success' => true, 'message' => 'Registro exitoso', 'nombre' => $nombre]);

    } catch (PDOException $e) {
        // El código de error 23000 en MySQL significa "Entrada duplicada" (el correo ya existe)
        if ($e->getCode() == 23000) {
            echo json_encode(['success' => false, 'message' => 'Este correo ya está registrado. Prueba a iniciar sesión.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
        }
    }

} else {
    // ==========================================
    // LÓGICA DE INICIO DE SESIÓN (LOGIN)
    // ==========================================
    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch();
    
    // Verificamos si el usuario existe y si la contraseña introducida coincide con el hash guardado
    if ($usuario && password_verify($password, $usuario['password'])) {
        
        // Guardamos los datos en la sesión
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];
        
        echo json_encode(['success' => true, 'message' => 'Login exitoso', 'nombre' => $usuario['nombre']]);
    } else {
        // Mensaje genérico por seguridad (no le decimos al hacker si falló el correo o la contraseña)
        echo json_encode(['success' => false, 'message' => 'Correo o contraseña incorrectos.']);
    }
}
?>