<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$id = $_SESSION['usuario_id'];

$stmt = $pdo->prepare("SELECT nombre, email, rol FROM usuarios WHERE id_usuario = :id");
$stmt->execute([':id' => $id]);
$usuario = $stmt->fetch();

$stmt2 = $pdo->prepare("
    SELECT p.id_pedido, p.fecha_pedido, p.total, p.estado,
           GROUP_CONCAT(pr.nombre SEPARATOR ', ') AS productos
    FROM pedidos p
    JOIN detalles_pedido dp ON dp.id_pedido = p.id_pedido
    JOIN productos pr       ON pr.id_producto = dp.id_producto
    WHERE p.id_usuario = :id
    GROUP BY p.id_pedido
    ORDER BY p.fecha_pedido DESC
");
$stmt2->execute([':id' => $id]);
$pedidos = $stmt2->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi perfil - BeatDrop</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/perfil.css">
    <link rel="stylesheet" href="css/chatbot.css">
</head>
<body>

<header>
    <h1>BeatDrop</h1>
    <nav>
        <a href="index.php">Inicio</a>
        <a href="albumes.php">Álbumes</a>
        <a href="carrito.html">Carrito</a>
        <span id="usuario-header" style="margin-left: 15px; font-weight: bold; color: #ff7a00;"></span>
    </nav>
    <button id="loginBtn">Iniciar sesión / Crear cuenta</button>
</header>

<main>
    <div class="perfil-container">

        <div class="perfil-card">
            <div class="perfil-avatar">👤</div>
            <div class="perfil-datos">
                <h2><?php echo htmlspecialchars($usuario['nombre']); ?></h2>
                <p>📧 <?php echo htmlspecialchars($usuario['email']); ?></p>
                <span class="rol-badge">
                    <?php echo $usuario['rol'] === 'admin' ? '⚙️ Administrador' : '🎵 Cliente'; ?>
                </span>
                <br>
                <form method="POST" action="cerrar_sesion.php" style="display:inline;">
                    <button type="submit" class="btn-cerrar-sesion">Cerrar sesión</button>
                </form>
            </div>
        </div>

        <div class="perfil-seccion">
            <h3>Historial de pedidos</h3>

            <?php if (count($pedidos) > 0): ?>
                <table class="tabla-pedidos">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Productos</th>
                            <th>Total</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td>#<?php echo $pedido['id_pedido']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($pedido['fecha_pedido'])); ?></td>
                                <td><?php echo htmlspecialchars($pedido['productos']); ?></td>
                                <td><strong style="color:#ff7a00;">$<?php echo number_format($pedido['total'], 2); ?></strong></td>
                                <td>
                                    <?php
                                        $estados = [
                                            'pendiente' => ['clase' => 'estado-pendiente', 'texto' => '⏳ Pendiente'],
                                            'simulado'  => ['clase' => 'estado-simulado',  'texto' => '✅ Confirmado'],
                                            'enviado'   => ['clase' => 'estado-enviado',   'texto' => '🚚 Enviado'],
                                        ];
                                        $e = $estados[$pedido['estado']] ?? ['clase' => '', 'texto' => $pedido['estado']];
                                    ?>
                                    <span class="estado-badge <?php echo $e['clase']; ?>">
                                        <?php echo $e['texto']; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="sin-pedidos">
                    <p>Todavía no tienes ningún pedido.</p>
                    <a href="albumes.php" class="btn-ir-tienda">🎵 Ir a la tienda</a>
                </div>
            <?php endif; ?>
        </div>

    </div>
</main>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-info">
            <h3 class="logo"><span class="dot"></span> BeatDrop</h3>
            <p class="descripcion">Tu tienda online de confianza para vinilos, CDs y cassettes.</p>
        </div>
        <div class="footer-contacto">
            <h4>Contacto</h4>
            <div class="contact-box">
                <p>Calle Música 123, Madrid</p>
                <p>+34 912 345 678</p>
                <p>info@beatdrop.es</p>
            </div>
        </div>
    </div>
    <div class="footer-bottom">© 2026 BeatDrop. Todos los derechos reservados.</div>
</footer>

<script src="js/login-modal.js"></script>

<button id="chat-abrir">🤖 Lara</button>
<div id="chat-ventana">
    <div id="chat-cabecera">
        <span id="chat-avatar-header">🤖</span>
        <div id="chat-cabecera-texto">
            <span id="chat-cabecera-nombre">Lara</span>
            <span id="chat-cabecera-estado">Asistente de BeatDrop</span>
        </div>
        <button id="chat-cerrar">&times;</button>
    </div>
    <div id="chat-mensajes"></div>
</div>
<script src="js/chatbot.js"></script>

</body>
</html>