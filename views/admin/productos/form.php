<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $modo === 'editar' ? 'Editar producto' : 'Crear producto'; ?> (Admin) - BeatDrop</title>
    <link rel="stylesheet" href="/css/global.css">
</head>
<body>
<header>
    <h1><?php echo $modo === 'editar' ? 'Editar producto' : 'Crear producto'; ?></h1>
    <nav>
        <a href="/admin/productos">Volver a productos</a>
        <a href="/admin">Dashboard</a>
        <a href="/">Volver al sitio</a>
    </nav>
</header>

<main>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">

        <div>
            <label>Nombre</label><br>
            <input name="nombre" required style="width:100%;" value="<?php echo htmlspecialchars($producto['nombre'] ?? ''); ?>">
        </div>

        <div style="margin-top:10px;">
            <label>Categoría</label><br>
            <select name="id_categoria">
                <option value="">—</option>
                <?php foreach ($categorias as $c): ?>
                    <option value="<?php echo (int)$c['id_categoria']; ?>" <?php echo ((string)($producto['id_categoria'] ?? '') === (string)$c['id_categoria']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($c['nombre_categoria']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-top:10px;">
            <label>Precio</label><br>
            <input name="precio" type="number" step="0.01" min="0" required value="<?php echo htmlspecialchars((string)($producto['precio'] ?? '0')); ?>">
        </div>

        <div style="margin-top:10px;">
            <label>Stock</label><br>
            <input name="stock" type="number" step="1" min="0" value="<?php echo htmlspecialchars((string)($producto['stock'] ?? '0')); ?>">
        </div>

        <div style="margin-top:10px;">
            <label>Imagen (ruta, ej: <code>img/future-album.jpg</code>)</label><br>
            <input name="imagen_url" style="width:100%;" value="<?php echo htmlspecialchars($producto['imagen_url'] ?? ''); ?>">
            <?php if (!empty($producto['imagen_url'])): ?>
                <div style="margin-top:6px;">
                    <img src="/<?php echo ltrim(htmlspecialchars($producto['imagen_url']), '/'); ?>" alt="preview" style="max-width:160px; max-height:160px;">
                </div>
            <?php endif; ?>
        </div>

        <div style="margin-top:10px;">
            <label>Descripción</label><br>
            <textarea name="descripcion" style="width:100%; min-height:80px;"><?php echo htmlspecialchars($producto['descripcion'] ?? ''); ?></textarea>
        </div>

        <div style="margin-top:10px;">
            <label>Género (si aplica)</label><br>
            <input name="genero" value="<?php echo htmlspecialchars($producto['genero'] ?? ''); ?>">
        </div>

        <div style="margin-top:10px;">
            <label>Formato (si aplica)</label><br>
            <input name="formato" value="<?php echo htmlspecialchars($producto['formato'] ?? ''); ?>">
        </div>

        <div style="margin-top:10px;">
            <label>Talla (si aplica)</label><br>
            <input name="talla" value="<?php echo htmlspecialchars($producto['talla'] ?? ''); ?>">
        </div>

        <div style="margin-top:14px;">
            <button type="submit"><?php echo $modo === 'editar' ? 'Guardar cambios' : 'Crear producto'; ?></button>
        </div>
    </form>
</main>
</body>
</html>

