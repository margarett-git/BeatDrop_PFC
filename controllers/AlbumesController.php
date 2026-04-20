<?php
class AlbumesController {
    public function index(): void {
        global $pdo;
        $productoModel = new Producto($pdo);
        $productos_db = $productoModel->obtenerTodos();
        include BASE_PATH . '/views/albumes.php';
    }
}
