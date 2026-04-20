<?php

class AdminProductosController {
    private $pdo;
    private Producto $productoModel;
    private Categoria $categoriaModel;

    public function __construct() {
        if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] ?? '') !== 'admin') {
            redirect('/');
        }
        global $pdo;
        $this->pdo = $pdo;
        $this->productoModel = new Producto($pdo);
        $this->categoriaModel = new Categoria($pdo);
    }

    public function index(): void {
        $productos = $this->productoModel->obtenerTodosConCategoria();
        include BASE_PATH . '/views/admin/productos/index.php';
    }

    public function crear($idCategoria = null): void {
        $categorias = $this->categoriaModel->obtenerTodas();

        if (is_post()) {
            csrf_require();
            $data = $this->leerFormularioProducto($_POST);
            $this->productoModel->crear($data);
            redirect('/admin/productos');
        }

        $producto = [
            'id_categoria' => $idCategoria !== null ? (int)$idCategoria : null,
        ];
        $modo = 'crear';
        include BASE_PATH . '/views/admin/productos/form.php';
    }

    public function editar($id): void {
        $id = (int)$id;
        $producto = $this->productoModel->obtenerPorId($id);
        if (!$producto) {
            http_response_code(404);
            echo 'Producto no encontrado';
            return;
        }

        $categorias = $this->categoriaModel->obtenerTodas();

        if (is_post()) {
            csrf_require();
            $data = $this->leerFormularioProducto($_POST);
            $this->productoModel->actualizar($id, $data);
            redirect('/admin/productos');
        }

        $modo = 'editar';
        include BASE_PATH . '/views/admin/productos/form.php';
    }

    public function eliminar($id): void {
        if (!is_post()) {
            http_response_code(405);
            echo 'Método no permitido';
            return;
        }
        csrf_require();
        $this->productoModel->eliminar((int)$id);
        redirect('/admin/productos');
    }

    private function leerFormularioProducto(array $input): array {
        $idCategoria = trim((string)($input['id_categoria'] ?? ''));
        $idCategoria = $idCategoria === '' ? null : (int)$idCategoria;

        $imagenUrl = trim((string)($input['imagen_url'] ?? ''));
        $imagenUrl = ltrim($imagenUrl, '/');
        if ($imagenUrl === '') {
            $imagenUrl = null;
        }

        $precio = (string)($input['precio'] ?? '0');
        $precio = (float)str_replace(',', '.', $precio);

        $stock = (int)($input['stock'] ?? 0);

        return [
            'id_categoria' => $idCategoria,
            'nombre' => trim((string)($input['nombre'] ?? '')),
            'descripcion' => trim((string)($input['descripcion'] ?? '')) ?: null,
            'precio' => $precio,
            'stock' => $stock,
            'imagen_url' => $imagenUrl,
            'genero' => trim((string)($input['genero'] ?? '')) ?: null,
            'formato' => trim((string)($input['formato'] ?? '')) ?: null,
            'talla' => trim((string)($input['talla'] ?? '')) ?: null,
        ];
    }
}
