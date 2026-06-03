<?php
require_once __DIR__ . '/../../config/database.php';

class InventoryModel {
    private $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    public function getTodosProdutos() {
        $sql = "SELECT 
                    p.id, 
                    p.codigo_sku, 
                    p.nome, 
                    p.categoria_id, 
                    p.preco_custo, 
                    p.quantidade_atual,
                    c.nome as categoria_nome 
                FROM produtos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                ORDER BY p.nome ASC";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getCategorias() {
        $sql = "SELECT id, nome FROM categorias ORDER BY nome ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}