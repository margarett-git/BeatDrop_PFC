<?php
// 1. Conexión a la base de datos usando PDO
require_once 'config/db.php';

// 2. Extraer los productos de la base de datos
try {
    // Si quisieras solo música, podrías añadir: WHERE id_categoria = 1
    $sql = "SELECT * FROM productos"; 
    $stmt = $pdo->query($sql);
    $productos_db = $stmt->fetchAll();
} catch (Exception $e) {
    die("Error al cargar el catálogo: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Álbumes - BeatDrop</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/albumes.css">
</head>
<body>

<header>
    <h1>BeatDrop</h1>
    <nav>
        <a href="index.php">Inicio</a>
        <a href="albumes.php" class="activo">Álbumes</a>
        <a href="carrito.html">Carrito</a>
        <span id="usuario-header" style="margin-left: 15px; font-weight: bold; color: #ff7a00;"></span>
    </nav>

    <button id="loginBtn">Iniciar sesión / Crear cuenta</button>
</header>

<section class="catalogo-container">
    <section class="catalogo">

        <h2>CATÁLOGO COMPLETO</h2>

        <aside class="filtros">
            <div class="filtro-grupo">
                <h4>Género</h4>
                <div class="botones-filtro">
                    <button class="active">Todos</button>
                    <button>Rock</button>
                    <button>Jazz</button>
                    <button>Pop</button>
                    <button>Electrónica</button>
                    <button>Hip-Hop</button>
                    <button>R&B</button>
                    <button>Blues</button>
                    <button>Reggae</button>
                </div>
            </div>

            <div class="filtro">
                <h4>Formato</h4>
                <div class="botones-filtro">
                    <button>Vinilo</button>
                    <button>CD</button>
                    <button>Cassette</button>
                </div>
            </div>
        </aside>

        <p class="productos-mostrados">Mostrando <?php echo count($productos_db); ?> productos</p>

        <section class="cards">
            
            <?php if (count($productos_db) > 0): ?>
                <?php foreach ($productos_db as $producto): ?>
                    
                    <article data-genero="<?php echo htmlspecialchars($producto['genero'] ?? ''); ?>" 
                             data-formato="<?php echo htmlspecialchars($producto['formato'] ?? ''); ?>">
                        
                        <img src="<?php echo !empty($producto['imagen_url']) ? htmlspecialchars($producto['imagen_url']) : 'img/future-album.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($producto['nombre']); ?>" class="album-img" />
                        
                        <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                        <p><?php echo htmlspecialchars($producto['genero'] ?? 'Varios'); ?> / <?php echo htmlspecialchars($producto['formato'] ?? 'Físico'); ?></p>
                        <p>Precio: $<?php echo number_format($producto['precio'], 2); ?></p>
                        
                        <?php if ($producto['stock'] > 0): ?>
                            <button>Añadir al carrito</button>
                        <?php else: ?>
                            <button disabled style="background-color: #555; cursor: not-allowed; border-color: #555;">Agotado</button>
                        <?php endif; ?>

                    </article>

                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay productos disponibles en este momento. ¡El catálogo está vacío!</p>
            <?php endif; ?>

        </section>
    </section>

</section>

<footer class="footer">
  <div class="footer-container">

    <div class="footer-info">
      <h3 class="logo">
        <span class="dot"></span> BeatDrop
      </h3>

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

<div id="loginModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2 id="modalTitle">Iniciar sesión</h2>
    <form id="loginForm">
      <input type="text" id="registerName" placeholder="Tu nombre" style="display: none; margin-bottom: 10px;">
      <input type="email" id="loginEmail" placeholder="Correo electrónico" required>
      <input type="password" id="loginPassword" placeholder="Contraseña" required>
      <button type="submit" id="loginSubmit" class="btn-submit">Entrar</button>
    </form>
    <p id="switchText">¿No tienes cuenta? <span id="switchMode">Regístrate</span></p>
  </div>
</div>

<script src="js/login-modal.js"></script>
<script src="js/albumes-carrito.js"></script>
<script src="js/filtros.js"></script>
<script src="js/carrito.js"></script> 


</body>
</html>