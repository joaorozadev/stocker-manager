<!DOCTYPE html>
<html lang="pt-br" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stocker Manager - Login</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="login-body">
    <button id="themeToggle" class="theme-toggle" aria-label="Mudar tema">🌙</button>

    <main class="login-wrapper">
        <section class="login-box">
            
            <div class="login-brand">
                <div class="brand-header">
                    <h1>Stocker<span>Manager</span></h1>
                    <p>A solução definitiva para controle de inventário e hardware.</p>
                </div>
                
                <div class="dynamic-info">
                    <div class="info-card">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </div>
                        <div class="info-text">
                            <p><strong>Mais Tempo</strong></p>
                            <p class="info-desc">Automatize tarefas repetitivas e foque no crescimento do negócio.</p>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        </div>
                        <div class="info-text">
                            <p><strong>Organização</strong></p>
                            <p class="info-desc">Tenha total controle do seu estoque em um só lugar.</p>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        </div>
                        <div class="info-text">
                            <p><strong>Segurança</strong></p>
                            <p class="info-desc">Rastreabilidade completa com segurança jurídica para o seu estoque.</p>
                        </div>
                    </div>
                </div>

                <div class="brand-footer">
                    <p>Versão 1.0.0-beta</p>
                    <p>&copy; <?= date('Y') ?> Stocker Manager</p>
                </div>
            </div>

            <div class="login-form-side">
                <h2>Bem-vindo de volta</h2>
                <p class="subtitle">Insira suas credenciais para acessar o painel.</p>

                <?php if (isset($_GET['error']) && $_GET['error'] === 'credentials'): ?>
                    <div class="alert alert-error">E-mail ou senha incorretos. Verifique e tente novamente.</div>
                <?php endif; ?>

                <?php if (isset($_GET['success']) && $_GET['success'] === 'created'): ?>
                    <div class="alert alert-success">Conta criada com sucesso! Você já pode fazer o login.</div>
                <?php endif; ?>

                <form id="loginForm" method="POST" action="index.php?action=login" novalidate>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" placeholder="seu@email.com" required>
                    </div>

                    <div class="form-group">
                        <div class="label-row">
                            <label for="password">Senha</label>
                            <a href="#" class="forgot-password">Esqueci minha senha</a>
                        </div>
                        
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" placeholder="••••••••" required>
                            <button type="button" id="togglePassword" class="toggle-password-btn" aria-label="Mostrar senha">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="auth-actions">
                        <button type="submit" id="btnSubmit" class="btn-primary">
                            <span class="btn-text">Entrar</span>
                            <div class="spinner hidden"></div> 
                        </button>
                        
                        <button type="button" id="btnDemo" class="btn-secondary" onclick="window.location.href='index.php?action=demo'">
                            Acesso Demo
                        </button>
                    </div>
                    
                    <footer class="form-footer">
                        <p>Ainda não tem acesso? <a href="index.php?page=signup">Criar uma conta</a></p>
                    </footer>
                </form>
            </div>
        </section>
    </main>
    <script src="js/main.js?v=<?= time(); ?>"></script>
</body>
</html>