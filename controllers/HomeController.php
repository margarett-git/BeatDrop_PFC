<?php
class HomeController {
    public function index(): void {
        global $pdo;
        $productoModel = new Producto($pdo);
        $destacados = $productoModel->obtenerDestacados();
        $novedades = $productoModel->obtenerNovedades();
        $ofertas = $productoModel->obtenerOfertas();
        include BASE_PATH . '/views/index.php';
    }
}
