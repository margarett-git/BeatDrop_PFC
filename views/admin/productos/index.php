<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos (Admin) - BeatDrop</title>
    <link rel="stylesheet" href="/css/global.css">
</head>
<body>
<header>
    <h1>Productos</h1>
    <nav>
        <a href="/admin">Dashboard</a>
        <a href="/admin/productos/crear">Nuevo producto</a>
        <a href="/">Volver al sitio</a>
    </nav>
</header>

<main>
    <h2>Listado</h2>

    <div style="overflow:auto;">
        <table style="width:100%; border-collapse: collapse;">
            <thead>
            <tr>
                <th style="text-align:left; padding:8px; border-bottom:1px solid #ddd;">ID</th>
                <th style="text-align:left; padding:8px; border-bottom:1px solid #ddd;">Nombre</th>
                <th style="text-align:left; padding:8px; border-bottom:1px solid #ddd;">Categoría</th>
                <th style="text-align:left; padding:8px; border-bottom:1px solid #ddd;">Precio</th>
                <th style="text-align:left; padding:8px; border-bottom:1px solid #ddd;">Stock</th>
                <th style="text-align:left; padding:8px; border-bottom:1px solid #ddd;">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($productos as $p): ?>
                <tr>
                    <td style="padding:8px; border-bottom:1px solid #eee;"><?php echo (int)$p['id_producto']; ?></td>
                    <td style="padding:8px; border-bottom:1px solid #eee;">
                        <?php echo htmlspecialchars($p['nombre']); ?>
                    </td>
                    <td style="padding:8px; border-bottom:1px solid #eee;">
                        <?php echo htmlspecialchars($p['nombre_categoria'] ?? ''); ?>
                    </td>
                    <td style="padding:8px; border-bottom:1px solid #eee;"><?php echo number_format((float)$p['precio'], 2); ?></td>
                    <td style="padding:8px; border-bottom:1px solid #eee;"><?php echo (int)($p['stock'] ?? 0); ?></td>
                    <td style="padding:8px; border-bottom:1px solid #eee; white-space:nowrap;">
                        <a href="/admin/productos/editar/<?php echo (int)$p['id_producto']; ?>">Editar</a>
                        <form method="post" action="/admin/productos/eliminar/<?php echo (int)$p['id_producto']; ?>" style="display:inline;" onsubmit="return confirm('¿Eliminar este producto?');">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>

