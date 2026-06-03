<?php
require_once __DIR__ . '/../../config/database.php';

class ProdutoModel {
    private $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    public function getCategorias() {
        $sql = "SELECT id, nome FROM categorias ORDER BY nome ASC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFornecedores() {
        $sql = "SELECT id, nome_fantasia FROM fornecedores ORDER BY nome_fantasia ASC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvarProduto($nome, $categoria_id, $fornecedor_id, $preco, $qtd) {
        try {
            $sku = 'PRD-' . strtoupper(substr(md5(uniqid()), 0, 4));

                $sql = "INSERT INTO produtos (nome, categoria_id, fornecedor_id, preco_custo, quantidade_atual, codigo_sku) 
                    VALUES (:nome, :categoria_id, :fornecedor_id, :preco, :qtd, :sku)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':nome'         => $nome,
                ':categoria_id' => $categoria_id,
                ':fornecedor_id' => $fornecedor_id,
                ':preco'        => $preco,
                ':qtd'          => $qtd,
                ':sku'          => $sku
            ]);

            return true;
            
        } catch (PDOException $e) {
            die("ERRO FATAL AO SALVAR PRODUTO: " . $e->getMessage());
        }
    }
    public function atualizarProduto($id, $nome, $categoria_id, $preco) {
        try {
            $sql = "UPDATE produtos SET nome = :nome, categoria_id = :categoria_id, preco_custo = :preco WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':nome'         => $nome,
                ':categoria_id' => $categoria_id,
                ':preco'        => $preco,
                ':id'           => $id
            ]);
            return true;
        } catch (PDOException $e) {
            die("ERRO FATAL AO ATUALIZAR PRODUTO: " . $e->getMessage());
        }
    }
}