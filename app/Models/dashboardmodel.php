<?php
require_once __DIR__ . '/../../config/database.php';

class DashboardModel {
    private $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    public function getCardsResumo() {
        $sqlValor = "SELECT COALESCE(SUM(quantidade_atual * preco_custo), 0) as total FROM produtos";
        $valorTotal = $this->pdo->query($sqlValor)->fetchColumn();

        $sqlItens = "SELECT COALESCE(SUM(quantidade_atual), 0) as total FROM produtos";
        $totalItens = $this->pdo->query($sqlItens)->fetchColumn();

        $sqlBaixo = "SELECT COUNT(id) as total FROM produtos WHERE quantidade_atual <= 5";
        $baixoStock = $this->pdo->query($sqlBaixo)->fetchColumn();

        return [
            'valor_stock' => $valorTotal,
            'total_itens' => $totalItens,
            'baixo_stock' => $baixoStock
        ];
    }

    public function getProdutosRecentes($limite = 5) {
        $sql = "SELECT 
                    p.id, 
                    p.codigo_sku, 
                    p.nome, 
                    p.categoria_id, 
                    p.preco_custo, 
                    c.nome as categoria_nome, 
                    p.quantidade_atual
                FROM produtos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                ORDER BY p.quantidade_atual ASC
                LIMIT :limite";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limite', (int) $limite, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getProdutosParaExportacao() {
        $sql = "SELECT p.codigo_sku, p.nome, c.nome as categoria, p.preco_custo, p.quantidade_atual 
                FROM produtos p 
                LEFT JOIN categorias c ON p.categoria_id = c.id 
                ORDER BY p.nome ASC";
                
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}