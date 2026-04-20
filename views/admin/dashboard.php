<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard administrador - BeatDrop</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body class="admin">
<div class="admin-shell">
    <aside class="admin-sidebar">
        <div class="admin-brand">
            <div class="title">BeatDrop</div>
            <div class="subtitle">Panel de Administración</div>
        </div>

        <nav class="admin-nav" aria-label="Menú del panel">
            <a href="/admin"><span>Dashboard</span><span class="badge">Admin</span></a>
            <a href="/admin/albumes"><span>Álbumes</span><span class="text-muted">Música</span></a>
            <a href="/admin/merchandising"><span>Merchandising</span><span class="text-muted">Tienda</span></a>
            <a href="/admin/productos"><span>Todos los productos</span><span class="text-muted">Catálogo</span></a>
        </nav>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <div class="meta">
                <div class="kicker">Sesión iniciada</div>
                <div class="headline">
                    Hola, <?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Admin'); ?>
                </div>
            </div>

            <div class="admin-actions">
                <a class="btn btn-primary" href="/admin/productos/crear">Añadir producto</a>
                <a class="btn btn-ghost" href="/">Ver tienda</a>
                <a class="btn btn-danger" href="/auth/logout">Cerrar sesión</a>
            </div>
        </header>

        <main class="admin-content">
            <section class="admin-hero">
                <h1>Dashboard</h1>
                <p>Controla el stock, actualiza precios y mantén el catálogo (álbumes y merchandising) siempre al día.</p>
            </section>

            <section class="admin-grid" aria-label="Accesos rápidos">
                <article class="card">
                    <h2>Álbumes</h2>
                    <p>Gestiona música: stock, precio, género y formato. Ideal para escanear rápido lo que falta.</p>
                    <div class="card-actions">
                        <a class="btn btn-primary" href="/admin/albumes">Entrar</a>
                        <a class="btn btn-ghost" href="/admin/productos/crear/1">Añadir álbum</a>
                    </div>
                </article>

                <article class="card">
                    <h2>Merchandising</h2>
                    <p>Controla tallas, unidades y precios. Mantén el inventario sano y evita ventas sin stock.</p>
                    <div class="card-actions">
                        <a class="btn btn-primary" href="/admin/merchandising">Entrar</a>
                        <a class="btn btn-ghost" href="/admin/productos/crear/2">Añadir merch</a>
                    </div>
                </article>

                <article class="card">
                    <h2>Catálogo completo</h2>
                    <p>Vista global para editar o eliminar productos. Perfecto para limpieza y ajustes masivos.</p>
                    <div class="card-actions">
                        <a class="btn btn-primary" href="/admin/productos">Ver listado</a>
                        <a class="btn btn-ghost" href="/admin/productos/crear">Añadir</a>
                    </div>
                </article>
            </section>
        </main>
    </div>
</div>
</body>
</html>
