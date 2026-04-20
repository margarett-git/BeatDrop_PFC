<?php

class Categoria {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerTodas(): array {
        $stmt = $this->pdo->query('SELECT * FROM categorias ORDER BY nombre_categoria ASC');
        return $stmt->fetchAll();
    }
}

