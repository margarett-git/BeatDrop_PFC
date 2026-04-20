<?php
class Usuario {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerPorId(int $id) {
        $stmt = $this->pdo->prepare('SELECT nombre, email, rol FROM usuarios WHERE id_usuario = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function obtenerPedidos(int $id): array {
        $stmt = $this->pdo->prepare(
            'SELECT p.id_pedido, p.fecha_pedido, p.total, p.estado, GROUP_CONCAT(pr.nombre SEPARATOR ", ") AS productos
             FROM pedidos p
             JOIN detalles_pedido dp ON dp.id_pedido = p.id_pedido
             JOIN productos pr ON pr.id_producto = dp.id_producto
             WHERE p.id_usuario = :id
             GROUP BY p.id_pedido
             ORDER BY p.fecha_pedido DESC'
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetchAll();
    }
}
