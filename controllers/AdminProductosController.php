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
            try {
                $data = $this->leerFormularioProducto($_POST, $_FILES);
            } catch (\RuntimeException $e) {
                $this->responderErrorFormulario($e->getMessage());
                return;
            }
            $idProducto = $this->productoModel->crear($data);

            if ($this->esPeticionAjax()) {
                json_response([
                    'success' => true,
                    'message' => 'Producto creado correctamente.',
                    'producto' => $this->serializarProducto($this->productoModel->obtenerPorIdConCategoria($idProducto)),
                ]);
            }

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
            try {
                $data = $this->leerFormularioProducto($_POST, $_FILES);
            } catch (\RuntimeException $e) {
                $this->responderErrorFormulario($e->getMessage());
                return;
            }
            $this->productoModel->actualizar($id, $data);

            if ($this->esPeticionAjax()) {
                json_response([
                    'success' => true,
                    'message' => 'Producto actualizado correctamente.',
                    'producto' => $this->serializarProducto($this->productoModel->obtenerPorIdConCategoria($id)),
                ]);
            }

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
        $id = (int)$id;
        $producto = $this->productoModel->obtenerPorId($id);
        if (!$producto) {
            if ($this->esPeticionAjax()) {
                json_response(['error' => 'Producto no encontrado'], 404);
            }
            http_response_code(404);
            echo 'Producto no encontrado';
            return;
        }

        $this->productoModel->eliminar($id);

        if ($this->esPeticionAjax()) {
            json_response([
                'success' => true,
                'message' => 'Producto eliminado correctamente.',
                'id_producto' => $id,
            ]);
        }

        redirect('/admin/productos');
    }

    public function agregarStock($id): void {
        if (!is_post()) {
            http_response_code(405);
            json_response(['error' => 'Método no permitido'], 405);
            return;
        }

        header('Content-Type: application/json');
        
        $id = (int)$id;
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $cantidad = (int)($input['cantidad'] ?? 0);

        if ($cantidad === 0) {
            json_response(['error' => 'Cantidad inválida'], 400);
            return;
        }

        $producto = $this->productoModel->obtenerPorId($id);
        if (!$producto) {
            json_response(['error' => 'Producto no encontrado'], 404);
            return;
        }

        $nuevoStock = (int)($producto['stock'] ?? 0) + $cantidad;
        if ($nuevoStock < 0) {
            $nuevoStock = 0;
        }
        $this->productoModel->actualizarStock($id, $nuevoStock);

        json_response([
            'success' => true,
            'nuevo_stock' => $nuevoStock,
            'producto' => $this->serializarProducto($this->productoModel->obtenerPorIdConCategoria($id)),
        ]);
    }

    private function leerFormularioProducto(array $input, array $files = []): array {
        $idCategoria = trim((string)($input['id_categoria'] ?? ''));
        $idCategoria = $idCategoria === '' ? null : (int)$idCategoria;

        $imagenUrl = $this->resolverImagenProducto($input, $files);

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

    private function esPeticionAjax(): bool {
        $requestedWith = strtolower((string)($_SERVER['HTTP_X_REQUESTED_WITH'] ?? ''));
        $accept = strtolower((string)($_SERVER['HTTP_ACCEPT'] ?? ''));

        return $requestedWith === 'xmlhttprequest' || str_contains($accept, 'application/json');
    }

    private function responderErrorFormulario(string $message): void {
        if ($this->esPeticionAjax()) {
            json_response(['error' => $message], 400);
        }

        http_response_code(400);
        echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    }

    private function resolverImagenProducto(array $input, array $files): ?string {
        $imagenActual = trim((string)($input['imagen_url_actual'] ?? ''));
        $imagenActual = $imagenActual !== '' ? ltrim($imagenActual, '/') : null;

        $archivo = $files['imagen_archivo'] ?? null;
        if (!is_array($archivo) || (int)($archivo['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return $imagenActual;
        }

        $error = (int)($archivo['error'] ?? UPLOAD_ERR_OK);
        if ($error !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('No se pudo subir la imagen.');
        }

        $nombreOriginal = (string)($archivo['name'] ?? '');
        $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
        if (!in_array($extension, ['jpg', 'jpeg'], true)) {
            throw new \RuntimeException('Solo se permiten imagenes JPG o JPEG.');
        }

        $tmpName = (string)($archivo['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            throw new \RuntimeException('El archivo subido no es valido.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = (string)$finfo->file($tmpName);
        if (!in_array($mimeType, ['image/jpeg', 'image/pjpeg'], true)) {
            throw new \RuntimeException('La portada debe ser una imagen JPG o JPEG.');
        }

        $uploadDir = BASE_PATH . '/public/uploads/productos';
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
            throw new \RuntimeException('No se pudo preparar la carpeta de imagenes.');
        }

        $fileName = 'producto-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.jpg';
        $destino = $uploadDir . '/' . $fileName;

        if (!move_uploaded_file($tmpName, $destino)) {
            throw new \RuntimeException('No se pudo guardar la imagen subida.');
        }

        return 'uploads/productos/' . $fileName;
    }

    private function serializarProducto($producto): array {
        if (!$producto) {
            return [];
        }

        return [
            'id_producto' => (int)$producto['id_producto'],
            'id_categoria' => $producto['id_categoria'] !== null ? (int)$producto['id_categoria'] : null,
            'nombre' => (string)($producto['nombre'] ?? ''),
            'descripcion' => $producto['descripcion'],
            'precio' => (float)($producto['precio'] ?? 0),
            'stock' => (int)($producto['stock'] ?? 0),
            'imagen_url' => (string)($producto['imagen_url'] ?? ''),
            'genero' => (string)($producto['genero'] ?? ''),
            'formato' => (string)($producto['formato'] ?? ''),
            'talla' => (string)($producto['talla'] ?? ''),
            'nombre_categoria' => (string)($producto['nombre_categoria'] ?? ''),
        ];
    }
}
