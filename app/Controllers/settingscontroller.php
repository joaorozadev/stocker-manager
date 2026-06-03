<?php
require_once __DIR__ . '/../Models/CategoriaModel.php';
require_once __DIR__ . '/../Models/Usuario.php';

class SettingsController {
    private $catModel;
    private $userModel;

    public function __construct() {
        $this->catModel = new CategoriaModel();
        $this->userModel = new Usuario();
    }

    public function index() {
        return [
            'categorias' => $this->catModel->listar(),
            'usuarios' => $this->userModel->listar()
        ];
    }

    public function salvarCategoria() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Administrador') {
                header('Location: index.php?page=settings&tab=seguranca&error=unauthorized');
                exit;
            }

            $nome = trim($_POST['nome'] ?? '');
            if (!empty($nome)) {
                $this->catModel->salvar($nome);
                header('Location: index.php?page=settings&tab=categorias&success=cat_saved');
            } else {
                header('Location: index.php?page=settings&tab=categorias&error=cat_empty');
            }
            exit;
        }
    }

    public function salvarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Administrador') {
                header('Location: index.php?page=settings&tab=seguranca&error=unauthorized');
                exit;
            }

            $nome = trim($_POST['nome'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $senha = $_POST['password'] ?? '';
            $cargo = $_POST['cargo'] ?? 'Operador';

            if (empty($nome) || empty($email) || empty($senha)) {
                header('Location: index.php?page=settings&tab=usuarios&error=fields_empty');
                exit;
            }

            if ($this->userModel->findByEmail($email)) {
                header('Location: index.php?page=settings&tab=usuarios&error=user_exists');
                exit;
            }

            if ($this->userModel->create($nome, $email, $senha, $cargo)) {
                header('Location: index.php?page=settings&tab=usuarios&success=user_saved');
            } else {
                header('Location: index.php?page=settings&tab=usuarios&error=failed');
            }
            exit;
        }
    }

    public function alterarSenha() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            
            $senhaAtual = $_POST['senha_atual'] ?? '';
            $novaSenha = $_POST['nova_senha'] ?? '';
            $confirmarSenha = $_POST['confirmar_senha'] ?? '';

            if (empty($senhaAtual) || empty($novaSenha) || empty($confirmarSenha)) {
                header('Location: index.php?page=settings&tab=seguranca&error=pw_empty');
                exit;
            }

            if ($novaSenha !== $confirmarSenha) {
                header('Location: index.php?page=settings&tab=seguranca&error=pw_mismatch');
                exit;
            }

            $stmt = $this->userModel->listar(); 
            $user = $this->userModel->findByEmail($_SESSION['demo_email'] ?? 'demo@stockermanager.com'); 
            require_once __DIR__ . '/../../config/database.php';
            $pdo = getConexao();
            $stmtUser = $pdo->prepare("SELECT senha FROM usuarios WHERE id = ?");
            $stmtUser->execute([$userId]);
            $userDb = $stmtUser->fetch();

            if ($userDb && password_verify($senhaAtual, $userDb['senha'])) {
                $novoHash = password_hash($novaSenha, PASSWORD_DEFAULT);
                $this->userModel->alterarSenha($userId, $novoHash);
                header('Location: index.php?page=settings&tab=seguranca&success=pw_updated');
            } else {
                header('Location: index.php?page=settings&tab=seguranca&error=pw_wrong');
            }
            exit;
        }
    }
}