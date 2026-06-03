<?php
require_once __DIR__ . '/../Models/suppliermodel.php';

class SupplierController {
    private $model;

    public function __construct() {
        $this->model = new SupplierModel();
    }

    public function index() {
        $busca = $_GET['search'] ?? '';
        $fornecedores = $this->model->getFornecedores($busca);

        return [
            'fornecedores' => $fornecedores
        ];
    }

    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            if (!isset($_SESSION['user_id'])) {
                header("Location: index.php?page=login");
                exit();
            }

            $nome = trim($_POST['nome_fantasia'] ?? '');
            $cnpj = trim($_POST['cnpj'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telefone = trim($_POST['telefone'] ?? '');

            if (!empty($nome)) {
                $this->model->salvar($nome, $cnpj, $email, $telefone);
            }

            header("Location: index.php?page=suppliers");
            exit();
        }
    }
    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            if (!isset($_SESSION['user_id'])) {
                header("Location: index.php?page=login");
                exit();
            }

            $id = (int)$_POST['id'];
            $nome = trim($_POST['nome_fantasia'] ?? '');
            $cnpj = trim($_POST['cnpj'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telefone = trim($_POST['telefone'] ?? '');

            if ($id > 0 && !empty($nome)) {
                $this->model->atualizar($id, $nome, $cnpj, $email, $telefone);
            }

            header("Location: index.php?page=suppliers");
            exit();
        }
    }

    public function apiProdutosFornecedor() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($id > 0) {
            $produtos = $this->model->getProdutosPorFornecedor($id);
            
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($produtos);
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([]);
        }
        exit();
    }
}