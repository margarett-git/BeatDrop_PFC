<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos (Admin) - BeatDrop</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/admin.css">
    <style>
        .productos-table-container {
            overflow-x: auto;
            margin-top: 1.5rem;
        }
        .productos-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--admin-panel, #fff);
            border: 1px solid var(--admin-border, #ddd);
            border-radius: 0.5rem;
        }
        .productos-table thead th {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid var(--admin-border, #ddd);
            background: var(--admin-panel-2, #f5f5f5);
            font-weight: 600;
        }
        .productos-table tbody td {
            padding: 12px;
            border-bottom: 1px solid var(--admin-border, #eee);
        }
        .productos-table tbody tr:hover {
            background: var(--admin-panel-2, rgba(0,0,0,0.02));
        }
        .btn-accion {
            display: inline-block;
            padding: 6px 12px;
            margin: 0 2px;
            background: var(--admin-accent, #ff7a00);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
        }
        .btn-accion:hover {
            background: var(--admin-accent-2, #ffb066);
        }
        .btn-accion.secondary {
            background: #666;
        }
        .btn-accion.secondary:hover {
            background: #555;
        }
        .btn-accion.danger {
            background: var(--admin-danger, #ff4c4c);
        }
        .btn-accion.danger:hover {
            background: #e63c3c;
        }

        /* Modal stock */
        .modal-stock {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .modal-stock.is-open {
            display: flex;
        }
        .modal-stock-content {
            background: var(--admin-panel, white);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 90%;
        }
        .modal-stock h2 {
            margin-bottom: 15px;
            color: var(--admin-text, black);
        }
        .modal-stock label {
            display: block;
            margin-bottom: 8px;
            color: var(--admin-text, black);
        }
        .modal-stock input {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--admin-border, #ddd);
            border-radius: 4px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }
        .modal-stock-buttons {
            display: flex;
            gap: 10px;
        }
        .modal-stock button {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }
        .modal-stock .btn-confirm {
            background: var(--admin-accent, #ff7a00);
            color: white;
        }
        .modal-stock .btn-confirm:hover {
            background: var(--admin-accent-2, #ffb066);
        }
        .modal-stock .btn-cancel {
            background: #666;
            color: white;
        }
        .modal-stock .btn-cancel:hover {
            background: #555;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .productos-table-container {
                margin-top: 1rem;
            }
            .productos-table {
                font-size: 0.9rem;
            }
            .productos-table thead th,
            .productos-table tbody td {
                padding: 8px;
            }
            .btn-accion {
                padding: 4px 8px;
                font-size: 0.8rem;
                margin: 1px;
            }
        }

        @media (max-width: 480px) {
            /* Convertir tabla a cards en móvil */
            .productos-table {
                display: block;
                border: none;
            }
            .productos-table thead {
                display: none;
            }
            .productos-table tbody, .productos-table tr {
                display: block;
                width: 100%;
            }
            .productos-table tr {
                border: 1px solid var(--admin-border, #ddd);
                border-radius: 4px;
                margin-bottom: 10px;
                padding: 10px;
                background: var(--admin-panel, white);
            }
            .productos-table td {
                display: block;
                padding: 6px 0;
                border: none;
                border-bottom: none !important;
            }
            .productos-table td:before {
                content: attr(data-label);
                font-weight: 600;
                display: inline-block;
                width: 80px;
            }
            .btn-accion {
                display: block;
                width: 100%;
                margin: 4px 0;
                padding: 8px;
            }
        }
    </style>
</head>
<body class="admin">
<header style="padding: 1rem; border-bottom: 1px solid #ddd; background: var(--admin-panel, white);">
    <h1 style="margin: 0;">Productos</h1>
    <nav style="margin-top: 0.5rem;">
        <a href="/admin" style="margin-right: 1rem; color: inherit;">Dashboard</a>
        <a href="/admin/productos/crear" style="margin-right: 1rem; color: inherit;">Nuevo producto</a>
        <a href="/" style="color: inherit;">Volver al sitio</a>
    </nav>
</header>

<main style="padding: 1.5rem;">
    <h2 style="margin-bottom: 1rem;">Listado de productos</h2>

    <div class="productos-table-container">
        <table class="productos-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($productos as $p): ?>
                <tr>
                    <td data-label="ID"><?php echo (int)$p['id_producto']; ?></td>
                    <td data-label="Nombre">
                        <?php echo htmlspecialchars($p['nombre']); ?>
                    </td>
                    <td data-label="Categoría">
                        <?php echo htmlspecialchars($p['nombre_categoria'] ?? '-'); ?>
                    </td>
                    <td data-label="Precio"><?php echo number_format((float)$p['precio'], 2); ?> €</td>
                    <td data-label="Stock">
                        <span><?php echo (int)($p['stock'] ?? 0); ?></span>
                    </td>
                    <td data-label="Acciones" style="white-space: normal;">
                        <button class="btn-accion secondary" onclick="abrirModalStock(<?php echo (int)$p['id_producto']; ?>, '<?php echo htmlspecialchars(addslashes($p['nombre'])); ?>')">
                            + Stock
                        </button>
                        <a href="/admin/productos/editar/<?php echo (int)$p['id_producto']; ?>" class="btn-accion">Editar</a>
                        <form method="post" action="/admin/productos/eliminar/<?php echo (int)$p['id_producto']; ?>" style="display:inline;" onsubmit="return confirm('¿Eliminar este producto?');">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
                            <button type="submit" class="btn-accion danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Modal para agregar stock -->
<div class="modal-stock" id="modalStock">
    <div class="modal-stock-content">
        <h2>Agregar stock</h2>
        <p id="stockProductName" style="margin-bottom: 1rem; color: var(--admin-muted, #666);"></p>
        <label for="stockQuantity">Cantidad a agregar:</label>
        <input type="number" id="stockQuantity" min="1" value="1" placeholder="Ej: 5">
        <div class="modal-stock-buttons">
            <button class="btn-confirm" onclick="confirmarAgregarStock()">Agregar</button>
            <button class="btn-cancel" onclick="cerrarModalStock()">Cancelar</button>
        </div>
    </div>
</div>

<script>
    let stockProductoId = null;
    
    function abrirModalStock(id, nombre) {
        stockProductoId = id;
        document.getElementById('stockProductName').textContent = nombre;
        document.getElementById('stockQuantity').value = '1';
        document.getElementById('modalStock').classList.add('is-open');
        document.getElementById('stockQuantity').focus();
    }
    
    function cerrarModalStock() {
        document.getElementById('modalStock').classList.remove('is-open');
        stockProductoId = null;
    }
    
    async function confirmarAgregarStock() {
        const cantidad = parseInt(document.getElementById('stockQuantity').value || 0);
        if (cantidad <= 0) {
            alert('Ingresa una cantidad válida');
            return;
        }
        
        try {
            const response = await fetch('/admin/productos/agregar-stock/' + stockProductoId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ cantidad: cantidad })
            });
            
            if (response.ok) {
                alert('Stock actualizado correctamente');
                location.reload();
            } else {
                alert('Error al actualizar stock');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al actualizar stock');
        }
    }
    
    // Cerrar modal con Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && document.getElementById('modalStock').classList.contains('is-open')) {
            cerrarModalStock();
        }
    });
    
    // Cerrar modal al clickear fuera
    document.getElementById('modalStock').addEventListener('click', (e) => {
        if (e.target.id === 'modalStock') {
            cerrarModalStock();
        }
    });
</script>
</body>
</html>

