<?php
class AdminController {
    public function dashboard(): void {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
            header('Location: /');
            exit;
        }

        include BASE_PATH . '/views/admin/dashboard.php';
    }
}
