<!DOCTYPE html>
<html lang="pt-br" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stocker Manager - Criar Conta</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="login-body">
    <button id="themeToggle" class="theme-toggle" aria-label="Mudar tema">🌙</button>

    <main class="login-wrapper">
        <section class="login-box">
            
            <div class="login-brand">
                <div></div> 

                <div class="brand-header-centered">
                    <h1>Stocker<span>Manager</span></h1>
                    <p>Comece a organizar seu inventário de forma profissional hoje mesmo.</p>
                </div>

                <div class="brand-footer">
                    <p>Versão 1.0.0-beta</p>
                    <p>&copy; <?= date('Y') ?> Stocker Manager.</p>
                </div>
            </div>

            <div class="login-form-side">
                <h2>Criar nova conta</h2>
                <p class="subtitle">Preencha os campos abaixo para começar.</p>

                <?php if (isset($_GET['error']) && $_GET['error'] === 'exists'): ?>
                    <div class="alert alert-error">Este e-mail já está cadastrado. Tente fazer o login.</div>
                <?php endif; ?>

                <?php if (isset($_GET['error']) && $_GET['error'] === 'failed'): ?>
                    <div class="alert alert-error">Ocorreu um erro ao criar a conta. Tente novamente mais tarde.</div>
                <?php endif; ?>

                <form id="signupForm" method="POST" action="index.php?action=register">
                    <div class="form-group">
                        <label for="nome">Nome de Usuário (Username)</label>
                        <input type="text" id="nome" name="nome" placeholder="Ex: Joao Pedro" required>
                    </div>

                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" placeholder="seu@email.com" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input type="password" id="password" name="password" placeholder="Mínimo 8 caracteres" required>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">Confirme sua Senha</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Digite a senha novamente" required>
                    </div>

                    <div class="auth-actions single-action">
                        <button type="submit" id="btnSignup" class="btn-primary">
                            <span class="btn-text">Criar Conta</span>
                            <div class="spinner hidden"></div>
                        </button>
                    </div>
                    
                    <footer class="form-footer">
                        <p>Já possui acesso? <a href="index.php?page=login">Fazer Login</a></p>
                    </footer>
                </form>
            </div>
        </section>
    </main>

    <script src="js/main.js?v=<?= time(); ?>"></script>
</body>
</html>