<?php
// 1. Conexión a la base de datos
require_once 'config/db.php';

try {
    // A. Artistas Destacados (Cogemos 3 discos al azar para que cambien cada vez)
    $stmt_destacados = $pdo->query("SELECT * FROM productos ORDER BY RAND() LIMIT 3");
    $destacados = $stmt_destacados->fetchAll();

    // B. Novedades (Cogemos los 3 últimos que se han añadido a la base de datos)
    $stmt_novedades = $pdo->query("SELECT * FROM productos ORDER BY id_producto DESC LIMIT 3");
    $novedades = $stmt_novedades->fetchAll();

    // C. Ofertas (Cogemos los 3 más baratos para la sección de ofertas)
    $stmt_ofertas = $pdo->query("SELECT * FROM productos ORDER BY precio ASC LIMIT 3");
    $ofertas = $stmt_ofertas->fetchAll();

} catch (Exception $e) {
    die("Error al cargar la página de inicio: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeatDrop | Inicio</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/albumes.css">
</head>
<body>

<header>
    <h1>BeatDrop</h1>
    <nav>
        <a href="index.php" class="activo">Inicio</a>
        <a href="albumes.php">Álbumes</a>
        <a href="carrito.html">Carrito</a>
        <span id="usuario-header" style="margin-left: 15px; font-weight: bold; color: #ff7a00;"></span>
    </nav>
    <button id="loginBtn">Iniciar sesión / Crear cuenta</button>
</header>

<main>
    <section class="catalogo">
        <h2>Artistas destacados</h2>
        <section class="cards">
            <?php foreach ($destacados as $prod): ?>
                <article>
                    <img src="<?php echo htmlspecialchars($prod['imagen_url']); ?>" alt="<?php echo htmlspecialchars($prod['nombre']); ?>" class="album-img" />
                    <h3><?php echo htmlspecialchars($prod['nombre']); ?></h3>
                    <p><?php echo htmlspecialchars($prod['genero']); ?> / <?php echo htmlspecialchars($prod['formato']); ?></p>
                    <p>Precio: $<?php echo number_format($prod['precio'], 2); ?></p> 
                    <?php if ($prod['stock'] > 0): ?>
                        <button>Añadir al carrito</button>
                    <?php else: ?>
                        <button disabled style="background-color: #555; cursor: not-allowed; border-color: #555;">Agotado</button>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </section>
    </section>

    <section class="novedades">
      <h2>Novedades</h2>
       <section class="cards">
            <?php foreach ($novedades as $prod): ?>
                <article>
                    <img src="<?php echo htmlspecialchars($prod['imagen_url']); ?>" alt="<?php echo htmlspecialchars($prod['nombre']); ?>" class="album-img" />
                    <h3><?php echo htmlspecialchars($prod['nombre']); ?></h3>
                    <p><?php echo htmlspecialchars($prod['genero']); ?> / <?php echo htmlspecialchars($prod['formato']); ?></p>
                    <p>Precio: $<?php echo number_format($prod['precio'], 2); ?></p>
                    <?php if ($prod['stock'] > 0): ?>
                        <button>Añadir al carrito</button>
                    <?php else: ?>
                        <button disabled style="background-color: #555; cursor: not-allowed; border-color: #555;">Agotado</button>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </section>
    </section>

    <section class="catalogo">
        <h2>Ofertas</h2>
        <section class="cards">
            <?php foreach ($ofertas as $prod): 
                // Simulamos un precio antiguo inflándolo un 20% para mantener vuestro diseño original
                $precio_antiguo = $prod['precio'] * 1.20;
            ?>
                <article>
                    <img src="<?php echo htmlspecialchars($prod['imagen_url']); ?>" alt="<?php echo htmlspecialchars($prod['nombre']); ?>" class="album-img" />
                    <h3><?php echo htmlspecialchars($prod['nombre']); ?></h3>
                    <p><?php echo htmlspecialchars($prod['genero']); ?> / <?php echo htmlspecialchars($prod['formato']); ?></p>
                    <p class="precio-oferta">Antes: $<?php echo number_format($precio_antiguo, 2); ?> → Ahora: $<?php echo number_format($prod['precio'], 2); ?></p>
                    <?php if ($prod['stock'] > 0): ?>
                        <button>Añadir al carrito</button>
                    <?php else: ?>
                        <button disabled style="background-color: #555; cursor: not-allowed; border-color: #555;">Agotado</button>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </section>
    </section>
</main>

<div id="loginModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2 id="modalTitle">Iniciar sesión</h2>
    
    <form id="loginForm">
      <input type="text" id="registerName" placeholder="Nombre" style="display: none; margin-bottom: 15px;">
      
      <input type="email" id="loginEmail" placeholder="Correo electrónico" required>
      <input type="password" id="loginPassword" placeholder="Contraseña" required>
      
      <button type="submit" id="loginSubmit" class="btn-submit">Entrar</button>
    </form>

    <p id="switchText">
      ¿No tienes cuenta? <span id="switchMode">Regístrate</span>
    </p>
  </div>
</div>

<footer class="footer">
  <div class="footer-container">
    <div class="footer-info">
      <h3 class="logo"><span class="dot"></span> BeatDrop</h3>
      <p class="descripcion">
        Tu tienda online de confianza para vinilos, CDs y cassettes.
        Música de calidad para verdaderos melómanos.
      </p>
    </div>
    <div class="footer-contacto">
      <h4>Contacto</h4>
      <div class="contact-box">
        <p>📍 Calle Música 123, Madrid</p>
        <p>📞 +34 912 345 678</p>
        <p>✉️ info@beatdrop.es</p>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    © 2026 BeatDrop. Todos los derechos reservados.
  </div>
</footer>

<script src="js/login-modal.js"></script>
<script src="js/albumes-carrito.js"></script>
<script src="js/carrito.js"></script> 

<div id="chatbot-preview" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000; font-family: sans-serif;">
    <div style="background: #ff7a00; color: white; padding: 15px; border-radius: 10px 10px 0 0; width: 250px; font-weight: bold;">
        Asistente BeatDrop
    </div>
    <div style="background: white; border: 1px solid #ccc; padding: 15px; width: 250px; height: 150px; font-size: 14px;">
        <p><strong>BeatBot:</strong> ¡Hola! Soy tu guía musical. ¿Cuál es tu presupuesto para hoy?</p>
        <p style="color: #777; font-style: italic;">Escribiendo...</p>
    </div>
    <input type="text" placeholder="Pregúntame algo..." style="width: 250px; padding: 10px; border: 1px solid #ccc; border-top: none;">
</div>

</body>
</html>