<?php
require_once __DIR__ . '/../../config/database.php';

class Usuario {
    private $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT id, nome, email, senha, cargo FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function create($nome, $email, $senha, $cargo = 'Administrador') {
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nome, email, senha, cargo) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nome, $email, $hash, $cargo]);
    }

    public function getOrCreateDemoUser() {
        $demoEmail = 'demo@stockermanager.com';
        $user = $this->findByEmail($demoEmail);
        
        if (!$user) {
            $this->create('Conta Demo', $demoEmail, 'demo123', 'Administrador');
            $user = $this->findByEmail($demoEmail);
        }
        
        return $user;
    }
    public function listar() {
        $stmt = $this->pdo->query("SELECT id, nome, email, cargo FROM usuarios ORDER BY nome ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function alterarSenha($id, $novaSenhaHash) {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
        return $stmt->execute([$novaSenhaHash, $id]);
    }
}