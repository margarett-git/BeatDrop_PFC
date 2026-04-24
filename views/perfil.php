<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi perfil - BeatDrop</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/perfil.css">
    <link rel="stylesheet" href="/css/chatbot.css">
</head>
<body>
<header>
    <h1>BeatDrop</h1>
    <nav>
        <a href="/">Inicio</a>
        <a href="/albumes">Álbumes</a>
        <a href="/carrito">Carrito</a>
        <span id="usuario-header"></span>
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
                <span class="rol-badge"><?php echo $usuario['rol'] === 'admin' ? '⚙️ Administrador' : '🎵 Cliente'; ?></span>
                <br>
                <form method="POST" action="/auth/logout" style="display:inline;">
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
                                    <span class="estado-badge <?php echo $e['clase']; ?>"><?php echo $e['texto']; ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="sin-pedidos">
                    <p>Todavía no tienes ningún pedido.</p>
                    <a href="/albumes" class="btn-ir-tienda">🎵 Ir a la tienda</a>
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
<script src="/js/login-modal.js"></script>
<button id="chat-abrir" type="button" aria-controls="chat-ventana" aria-expanded="false">
    <span class="chat-launcher-mark">BD</span>
    <span class="chat-launcher-copy">
        <strong>Lara</strong>
        <span>Ayuda personalizada</span>
    </span>
</button>
<div id="chat-ventana" aria-hidden="true">
    <div id="chat-cabecera">
        <div class="chat-brand">
            <div class="chat-brand-mark">BD</div>
            <div id="chat-cabecera-texto">
                <span id="chat-cabecera-nombre">Lara</span>
                <span id="chat-cabecera-estado">Asistente de BeatDrop</span>
            </div>
        </div>
        <button id="chat-cerrar" type="button" aria-label="Cerrar chat">&times;</button>
    </div>
    <div id="chat-mensajes"></div>
</div>
<script src="/js/chatbot.js"></script>
</body>
</html>
