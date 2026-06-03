<?php
require_once __DIR__ . '/../../config/database.php';

class SupplierModel {
    private $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    public function getFornecedores($busca = '') {
        $sql = "SELECT id, nome_fantasia, email, cnpj, telefone FROM fornecedores";
        
        if (!empty($busca)) {
            $sql .= " WHERE nome_fantasia ILIKE :busca OR cnpj ILIKE :busca";
        }
        
        $sql .= " ORDER BY nome_fantasia ASC";
        
        $stmt = $this->pdo->prepare($sql);
        
        if (!empty($busca)) {
            $stmt->bindValue(':busca', '%' . $busca . '%');
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvar($nome_fantasia, $cnpj, $email, $telefone) {
        $sql = "INSERT INTO fornecedores (nome_fantasia, cnpj, email, telefone) 
                VALUES (:nome, :cnpj, :email, :telefone)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nome' => $nome_fantasia,
            ':cnpj' => $cnpj,
            ':email' => $email,
            ':telefone' => $telefone
        ]);
    }
    public function atualizar($id, $nome_fantasia, $cnpj, $email, $telefone) {
        $sql = "UPDATE fornecedores 
                SET nome_fantasia = :nome, cnpj = :cnpj, email = :email, telefone = :telefone 
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nome' => $nome_fantasia,
            ':cnpj' => $cnpj,
            ':email' => $email,
            ':telefone' => $telefone,
            ':id' => $id
        ]);
    }

    public function getProdutosPorFornecedor($fornecedor_id) {
        $sql = "SELECT codigo_sku, nome, quantidade_atual 
                FROM produtos 
                WHERE fornecedor_id = :id 
                ORDER BY nome ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $fornecedor_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}