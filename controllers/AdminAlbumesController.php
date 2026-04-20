<?php

class AdminAlbumesController {
    private Producto $productoModel;

    public function __construct() {
        if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] ?? '') !== 'admin') {
            redirect('/');
        }
        global $pdo;
        $this->productoModel = new Producto($pdo);
    }

    public function index(): void {
        $idCategoria = 1; // Música
        $seccionTitulo = 'Álbumes (Música)';
        $productos = $this->productoModel->obtenerPorCategoriaConCategoria($idCategoria);
        $crearUrl = '/admin/productos/crear/' . $idCategoria;
        include BASE_PATH . '/views/admin/section_productos.php';
    }
}

