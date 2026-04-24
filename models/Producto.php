<?php
class Producto {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerTodos(): array {
        $stmt = $this->pdo->query('SELECT * FROM productos');
        return $stmt->fetchAll();
    }

    public function obtenerTodosConCategoria(): array {
        $stmt = $this->pdo->query(
            'SELECT p.*, c.nombre_categoria
             FROM productos p
             LEFT JOIN categorias c ON c.id_categoria = p.id_categoria
             ORDER BY p.id_producto DESC'
        );
        return $stmt->fetchAll();
    }

    public function obtenerPorCategoriaConCategoria(int $idCategoria): array {
        $stmt = $this->pdo->prepare(
            'SELECT p.*, c.nombre_categoria
             FROM productos p
             LEFT JOIN categorias c ON c.id_categoria = p.id_categoria
             WHERE p.id_categoria = :id_categoria
             ORDER BY p.id_producto DESC'
        );
        $stmt->execute([':id_categoria' => $idCategoria]);
        return $stmt->fetchAll();
    }

    public function obtenerPorId(int $id) {
        $stmt = $this->pdo->prepare('SELECT * FROM productos WHERE id_producto = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function obtenerPorIdConCategoria(int $id) {
        $stmt = $this->pdo->prepare(
            'SELECT p.*, c.nombre_categoria
             FROM productos p
             LEFT JOIN categorias c ON c.id_categoria = p.id_categoria
             WHERE p.id_producto = :id'
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function crear(array $data): int {
        $stmt = $this->pdo->prepare(
            'INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, genero, formato, talla)
             VALUES (:id_categoria, :nombre, :descripcion, :precio, :stock, :imagen_url, :genero, :formato, :talla)'
        );
        $stmt->execute([
            ':id_categoria' => $data['id_categoria'],
            ':nombre' => $data['nombre'],
            ':descripcion' => $data['descripcion'],
            ':precio' => $data['precio'],
            ':stock' => $data['stock'],
            ':imagen_url' => $data['imagen_url'],
            ':genero' => $data['genero'],
            ':formato' => $data['formato'],
            ':talla' => $data['talla'],
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function actualizar(int $id, array $data): void {
        $stmt = $this->pdo->prepare(
            'UPDATE productos
             SET id_categoria = :id_categoria,
                 nombre = :nombre,
                 descripcion = :descripcion,
                 precio = :precio,
                 stock = :stock,
                 imagen_url = :imagen_url,
                 genero = :genero,
                 formato = :formato,
                 talla = :talla
             WHERE id_producto = :id'
        );
        $stmt->execute([
            ':id' => $id,
            ':id_categoria' => $data['id_categoria'],
            ':nombre' => $data['nombre'],
            ':descripcion' => $data['descripcion'],
            ':precio' => $data['precio'],
            ':stock' => $data['stock'],
            ':imagen_url' => $data['imagen_url'],
            ':genero' => $data['genero'],
            ':formato' => $data['formato'],
            ':talla' => $data['talla'],
        ]);
    }

    public function actualizarStock(int $id, int $stock): void {
        $stmt = $this->pdo->prepare(
            'UPDATE productos
             SET stock = :stock
             WHERE id_producto = :id'
        );
        $stmt->execute([
            ':id' => $id,
            ':stock' => $stock,
        ]);
    }

    public function eliminar(int $id): void {
        $stmt = $this->pdo->prepare('DELETE FROM productos WHERE id_producto = :id');
        $stmt->execute([':id' => $id]);
    }

    public function obtenerDestacados(): array {
        $stmt = $this->pdo->query('SELECT * FROM productos ORDER BY RAND() LIMIT 3');
        return $stmt->fetchAll();
    }

    public function obtenerNovedades(): array {
        $stmt = $this->pdo->query('SELECT * FROM productos ORDER BY id_producto DESC LIMIT 3');
        return $stmt->fetchAll();
    }

    public function obtenerOfertas(): array {
        $stmt = $this->pdo->query('SELECT * FROM productos ORDER BY precio ASC LIMIT 3');
        return $stmt->fetchAll();
    }
}
