<?php
class PerfilController {
    public function index(): void {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /');
            exit;
        }

        global $pdo;
        $usuarioModel = new Usuario($pdo);
        $usuario = $usuarioModel->obtenerPorId($_SESSION['usuario_id']);
        $pedidos = $usuarioModel->obtenerPedidos($_SESSION['usuario_id']);
        include BASE_PATH . '/views/perfil.php';
    }
}
