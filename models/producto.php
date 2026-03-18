<?php
// model/Producto.php

function obtenerTodosLosProductos($pdo) {
    // Consulta simple para traer el catálogo [cite: 18, 65]
    $sql = "SELECT * FROM productos";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}