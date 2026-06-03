<?php
require_once __DIR__ . '/../Models/produtomodel.php';

class ProdutoController {
    private $model;

    public function __construct() {
        $this->model = new ProdutoModel();
    }

    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome       = trim($_POST['prodNome'] ?? '');
            $categoria  = $_POST['prodCategoria'] ?? null;
            $fornecedor = $_POST['prodFornecedor'] ?? null;
            $qtd        = (int) ($_POST['prodQtd'] ?? 0);
            
            $precoString = str_replace(',', '.', $_POST['prodPreco'] ?? '0');
            $preco = (float) $precoString;

            if (!empty($nome) && !empty($categoria)) {
                $this->model->salvarProduto($nome, $categoria, $fornecedor, $preco, $qtd);
            }

            $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=inventory';
            header("Location: " . $referer);
            exit();
        }
    }
    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id        = $_POST['editProdId'] ?? null;
            $nome      = trim($_POST['editProdNome'] ?? '');
            $categoria = $_POST['editProdCategoria'] ?? null;
            
            $precoString = str_replace(',', '.', $_POST['editProdPreco'] ?? '0');
            $preco = (float) $precoString;

            if ($id && !empty($nome) && !empty($categoria)) {
                $this->model->atualizarProduto($id, $nome, $categoria, $preco);
            }

            $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=dashboard';
            header("Location: " . $referer);
            exit();
        }
    }
}