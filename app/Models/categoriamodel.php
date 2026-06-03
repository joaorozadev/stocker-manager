<?php
require_once __DIR__ . '/../../config/database.php';

class CategoriaModel {
    private $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    public function listar() {
        $sql = "SELECT id, nome FROM categorias ORDER BY nome ASC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvar($nome) {
        $stmtCheck = $this->pdo->prepare("SELECT id FROM categorias WHERE nome = ?");
        $stmtCheck->execute([$nome]);
        if ($stmtCheck->fetch()) {
            return false;
        }

        $stmt = $this->pdo->prepare("INSERT INTO categorias (nome) VALUES (?)");
        return $stmt->execute([$nome]);
    }
}