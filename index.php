<?php
require_once 'config/db.php';
require_once 'models/Producto.php';

$productos = obtenerTodosLosProductos($pdo);

echo "<h1>Listado de Productos BeatDrop</h1>";
foreach ($productos as $p) {
    echo "<li>" . $p['nombre'] . " - " . $p['precio'] . "€ (Stock: " . $p['stock'] . ")</li>";
}
?>