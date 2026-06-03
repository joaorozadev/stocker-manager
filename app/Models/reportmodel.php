<?php
require_once __DIR__ . '/../../config/database.php';

class ReportModel {
    private $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    public function getAnosDisponiveis() {
        $sql = "SELECT DISTINCT EXTRACT(YEAR FROM data_movimento) as ano 
                FROM movimentacoes 
                ORDER BY ano DESC";
        $stmt = $this->pdo->query($sql);
        $anos = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        return empty($anos) ? [date('Y')] : $anos;
    }

    public function getFluxoMensal($ano) {
        $sql = "SELECT 
                    EXTRACT(MONTH FROM data_movimento) as mes,
                    tipo,
                    SUM(quantidade) as total_itens
                FROM movimentacoes
                WHERE EXTRACT(YEAR FROM data_movimento) = :ano
                GROUP BY EXTRACT(MONTH FROM data_movimento), tipo";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':ano', $ano, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getFluxoDiario($ano, $mes) {
        $sql = "SELECT 
                    EXTRACT(DAY FROM data_movimento) as dia,
                    tipo,
                    SUM(quantidade) as total_itens
                FROM movimentacoes
                WHERE EXTRACT(YEAR FROM data_movimento) = :ano
                  AND EXTRACT(MONTH FROM data_movimento) = :mes
                GROUP BY EXTRACT(DAY FROM data_movimento), tipo";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':ano', $ano, PDO::PARAM_INT);
        $stmt->bindValue(':mes', $mes, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getValorPorCategoria($ano = 'geral') {  
        $where = "";
        if ($ano !== 'geral') {
            $where = "WHERE EXTRACT(YEAR FROM m.data_movimento) = :ano";
        }

        $sql = "SELECT 
                    COALESCE(c.nome, 'Geral') as categoria, 
                    SUM(m.quantidade * p.preco_custo) as valor_total
                FROM movimentacoes m
                JOIN produtos p ON m.produto_id = p.id
                LEFT JOIN categorias c ON p.categoria_id = c.id
                $where
                GROUP BY c.nome
                ORDER BY valor_total DESC";

        $stmt = $this->pdo->prepare($sql);
        if ($ano !== 'geral') {
            $stmt->bindValue(':ano', (int)$ano, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProdutosMaiorGiro($limite = 5, $ano = 'geral') {
        $where = "";
        $params = [];
        
        if ($ano !== 'geral') {
            $where = "WHERE EXTRACT(YEAR FROM m.data_movimento) = :ano";
            $params[':ano'] = $ano;
        }

        $sql = "SELECT 
                    p.codigo_sku, 
                    p.nome, 
                    c.nome as categoria, 
                    SUM(CASE WHEN m.tipo = 'entrada' THEN m.quantidade ELSE 0 END) as total_entradas,
                    SUM(CASE WHEN m.tipo = 'saida' THEN m.quantidade ELSE 0 END) as total_saidas,
                    p.quantidade_atual
                FROM movimentacoes m
                JOIN produtos p ON m.produto_id = p.id
                LEFT JOIN categorias c ON p.categoria_id = c.id
                $where
                GROUP BY p.codigo_sku, p.nome, c.nome, p.quantidade_atual
                ORDER BY (SUM(CASE WHEN m.tipo = 'entrada' THEN m.quantidade ELSE 0 END) + SUM(CASE WHEN m.tipo = 'saida' THEN m.quantidade ELSE 0 END)) DESC
                LIMIT :limite";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        if ($ano !== 'geral') {
            $stmt->bindValue(':ano', (int)$ano, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFluxoAnualGlobal() {
        $sql = "SELECT 
                    EXTRACT(YEAR FROM data_movimento) as ano,
                    tipo,
                    SUM(quantidade) as total_itens
                FROM movimentacoes
                GROUP BY EXTRACT(YEAR FROM data_movimento), tipo
                ORDER BY ano ASC";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}