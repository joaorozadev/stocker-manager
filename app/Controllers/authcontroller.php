<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Models/Usuario.php';

class AuthController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $senha = $_POST['password'] ?? '';

            $user = $this->usuarioModel->findByEmail($email);

            if ($user && password_verify($senha, $user['senha'])) {

                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nome'];
                $_SESSION['user_role'] = $user['cargo'];
                
                header('Location: index.php?page=dashboard');
                exit;
            } else {
                header('Location: index.php?page=login&error=credentials');
                exit;
            }
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $senha = $_POST['password'] ?? '';

            if ($this->usuarioModel->findByEmail($email)) {
                header('Location: index.php?page=signup&error=exists');
                exit;
            }

            if ($this->usuarioModel->create($nome, $email, $senha)) {
                header('Location: index.php?page=login&success=created');
                exit;
            } else {
                header('Location: index.php?page=signup&error=failed');
                exit;
            }
        }
    }

    public function demo() {
        $user = $this->usuarioModel->getOrCreateDemoUser();

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nome'];
        $_SESSION['user_role'] = $user['cargo'];
        
        header('Location: index.php?page=dashboard');
        exit;
    }

    public function logout() {
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy(); 
        header('Location: index.php?page=login');
        exit;
    }
}