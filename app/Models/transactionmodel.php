<?php
require_once __DIR__ . '/../../config/database.php';

class TransactionModel {
    private $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    public function listarMovimentacoes($tipo = 'todos', $dataInicio = '', $dataFim = '', $busca = '', $limit = 10, $offset = 0) {
        $sql = "SELECT 
                    m.id,
                    m.tipo AS tipo_movimento,
                    m.quantidade,
                    m.motivo AS observacao,
                    m.data_movimento,
                    p.nome AS produto_nome,
                    p.codigo_sku,
                    u.nome AS usuario_nome
                FROM movimentacoes m
                JOIN produtos p ON m.produto_id = p.id
                LEFT JOIN usuarios u ON m.usuario_id = u.id
                WHERE 1=1"; 

        $params = [];

        if ($tipo === 'entrada' || $tipo === 'saida') {
            $sql .= " AND m.tipo = :tipo";
            $params[':tipo'] = $tipo;
        }

        if (!empty($dataInicio)) {
            $sql .= " AND DATE(m.data_movimento) >= :data_inicio";
            $params[':data_inicio'] = $dataInicio;
        }
        if (!empty($dataFim)) {
            $sql .= " AND DATE(m.data_movimento) <= :data_fim";
            $params[':data_fim'] = $dataFim;
        }

        if (!empty($busca)) {
            $sql .= " AND (p.codigo_sku ILIKE :busca OR p.nome ILIKE :busca OR u.nome ILIKE :busca OR m.motivo ILIKE :busca)";
            $params[':busca'] = '%' . $busca . '%';
        }

        $sql .= " ORDER BY m.data_movimento DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalMovimentacoes($tipo = 'todos', $dataInicio = '', $dataFim = '', $busca = '') {
        $sql = "SELECT COUNT(m.id)
                FROM movimentacoes m
                JOIN produtos p ON m.produto_id = p.id
                LEFT JOIN usuarios u ON m.usuario_id = u.id
                WHERE 1=1"; 

        $params = [];

        if ($tipo === 'entrada' || $tipo === 'saida') {
            $sql .= " AND m.tipo = :tipo";
            $params[':tipo'] = $tipo;
        }

        if (!empty($dataInicio)) {
            $sql .= " AND DATE(m.data_movimento) >= :data_inicio";
            $params[':data_inicio'] = $dataInicio;
        }
        if (!empty($dataFim)) {
            $sql .= " AND DATE(m.data_movimento) <= :data_fim";
            $params[':data_fim'] = $dataFim;
        }

        if (!empty($busca)) {
            $sql .= " AND (p.codigo_sku ILIKE :busca OR p.nome ILIKE :busca OR u.nome ILIKE :busca OR m.motivo ILIKE :busca)";
            $params[':busca'] = '%' . $busca . '%';
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public function getProdutosParaSelect() {
        $sql = "SELECT id, codigo_sku, nome FROM produtos ORDER BY nome ASC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getProdutoById($id) {
        $sql = "SELECT id, nome, quantidade_atual FROM produtos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrarMovimento($produto_id, $usuario_id, $tipo, $quantidade, $motivo) {
        try {
            $this->pdo->beginTransaction();

            $sqlMov = "INSERT INTO movimentacoes (produto_id, usuario_id, tipo, quantidade, motivo) 
                       VALUES (:produto_id, :usuario_id, :tipo, :qtd, :motivo)";
            $stmtMov = $this->pdo->prepare($sqlMov);
            $stmtMov->execute([
                ':produto_id' => $produto_id,
                ':usuario_id' => $usuario_id, 
                ':tipo'       => $tipo,
                ':qtd'        => $quantidade,
                ':motivo'     => $motivo
            ]);

            $operador = ($tipo === 'entrada') ? '+' : '-';
            $sqlProd = "UPDATE produtos SET quantidade_atual = quantidade_atual {$operador} :qtd WHERE id = :id";
            
            $stmtProd = $this->pdo->prepare($sqlProd);
            $stmtProd->execute([':qtd' => $quantidade, ':id' => $produto_id]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack(); 
            die("ERRO FATAL NA TRANSAÇÃO: " . $e->getMessage());
        }
    }
    public function getHistoricoExportacao() {

        $sql = "SELECT 
                    m.data_movimento, 
                    p.codigo_sku, 
                    p.nome as produto_nome, 
                    m.tipo, 
                    m.quantidade, 
                    m.motivo, 
                    u.nome as usuario_nome
                FROM movimentacoes m
                JOIN produtos p ON m.produto_id = p.id
                LEFT JOIN usuarios u ON m.usuario_id = u.id
                ORDER BY m.data_movimento DESC";
                
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}