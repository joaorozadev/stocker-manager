<!DOCTYPE html>
<html lang="pt-br" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stocker Manager - Configurações</title>
    <link rel="stylesheet" href="css/style.css?v=<?= time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="dashboard-body">

    <div class="app-container">
        <?php include __DIR__ . '/partials/sidebar.php'; ?>

        <main class="main-content">
            <header class="topbar">
                <div class="page-title">
                    <h1>Configurações do Sistema</h1>
                </div>
            </header>

            <nav class="settings-tabs">
                <button class="tab-btn active" data-tab="seguranca">
                    <i class="fa-solid fa-lock"></i> Minha Conta & Segurança
                </button>
                
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'Administrador'): ?>
                    <button class="tab-btn" data-tab="usuarios">
                        <i class="fa-solid fa-users"></i> Gerenciar Usuários
                    </button>
                    <button class="tab-btn" data-tab="categorias">
                        <i class="fa-solid fa-tags"></i> Categorias
                    </button>
                <?php endif; ?>
            </nav>

            <div class="settings-content-wrapper">

                <div id="tab-seguranca" class="tab-content active">
                    <section class="table-section settings-small-card">
                        <div class="table-header">
                            <h2>Alterar Minha Senha</h2>
                        </div>
                        <div class="settings-form-padding">
                            <?php if(isset($_GET['success']) && $_GET['success'] === 'pw_updated'): ?>
                                <div class="alert alert-success">Senha atualizada com sucesso!</div>
                            <?php json_encode($_GET); endif; ?>
                            <?php if(isset($_GET['error']) && $_GET['error'] === 'pw_wrong'): ?>
                                <div class="alert alert-error">A senha atual informada está incorreta.</div>
                            <?php endif; ?>
                            <?php if(isset($_GET['error']) && $_GET['error'] === 'pw_mismatch'): ?>
                                <div class="alert alert-error">As novas senhas não coincidem.</div>
                            <?php endif; ?>

                            <form method="POST" action="index.php?action=alterar_senha">
                                <div class="form-group">
                                    <label for="senhaAtual">Senha Atual</label>
                                    <input type="password" id="senhaAtual" name="senha_atual" required>
                                </div>
                                <div class="form-group">
                                    <label for="novaSenha">Nova Senha</label>
                                    <input type="password" id="novaSenha" name="nova_senha" placeholder="Mínimo 8 caracteres" required>
                                </div>
                                <div class="form-group">
                                    <label for="confirmarSenha">Confirmar Nova Senha</label>
                                    <input type="password" id="confirmarSenha" name="confirmar_senha" required>
                                </div>
                                <button type="submit" class="btn-primary settings-wide-btn">
                                    <i class="fa-solid fa-key"></i> Atualizar Senha
                                </button>
                            </form>
                        </div>
                    </section>
                </div>

                <div id="tab-usuarios" class="tab-content">
                    <div class="settings-grid">
                        <section class="table-section settings-aside-form">
                            <div class="table-header">
                                <h2>Novo Usuário</h2>
                            </div>
                            <div class="settings-form-padding">
                                <?php if(isset($_GET['success']) && $_GET['success'] === 'user_saved'): ?>
                                    <div class="alert alert-success">Usuário cadastrado com sucesso!</div>
                                <?php endif; ?>
                                <?php if(isset($_GET['error']) && $_GET['error'] === 'user_exists'): ?>
                                    <div class="alert alert-error">Este e-mail já está cadastrado no sistema.</div>
                                <?php endif; ?>

                                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'Administrador'): ?>
                                <form method="POST" action="index.php?action=salvar_usuario">
                                    <div class="form-group">
                                        <label for="nomeUser">Nome Completo *</label>
                                        <input type="text" id="nomeUser" name="nome" placeholder="Ex: Carlos Silva" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="emailUser">E-mail Corporativo *</label>
                                        <input type="email" id="emailUser" name="email" placeholder="nome@empresa.com" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="senhaUser">Senha de Acesso *</label>
                                        <input type="password" id="senhaUser" name="password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="cargoUser">Nível de Acesso (Cargo) *</label>
                                        <select id="cargoUser" name="cargo" class="sort-select settings-select-fix">
                                            <option value="Administrador">Administrador</option>
                                            <option value="Operador">Operador</option>
                                            <option value="Gerente">Gerente</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn-primary settings-wide-btn">
                                        <i class="fa-solid fa-user-plus"></i> Cadastrar Usuário
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </section>

                        <section class="table-section">
                            <div class="table-header">
                                <h2>Usuários Ativos</h2>
                            </div>
                            <div class="table-responsive">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>E-mail</th>
                                            <th>Cargo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($usuarios as $u): ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($u['nome']) ?></strong></td>
                                                <td><?= htmlspecialchars($u['email']) ?></td>
                                                <td><span class="badge stock"><?= htmlspecialchars($u['cargo']) ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                </div>

                <div id="tab-categorias" class="tab-content">
                    <div class="settings-grid">
                        <section class="table-section settings-aside-form">
                            <div class="table-header">
                                <h2>Nova Categoria</h2>
                            </div>
                            <div class="settings-form-padding">
                                <?php if(isset($_GET['success']) && $_GET['success'] === 'cat_saved'): ?>
                                    <div class="alert alert-success">Categoria adicionada com sucesso!</div>
                                <?php endif; ?>
                                
                                <form method="POST" action="index.php?action=salvar_categoria">
                                    <div class="form-group">
                                        <label for="nomeCat">Nome da Categoria *</label>
                                        <input type="text" id="nomeCat" name="nome" placeholder="Ex: Monitores" required>
                                    </div>
                                    <button type="submit" class="btn-primary settings-wide-btn">
                                        <i class="fa-solid fa-plus"></i> Adicionar Categoria
                                    </button>
                                </form>
                            </div>
                        </section>

                        <section class="table-section">
                            <div class="table-header">
                                <h2>Categorias Cadastradas</h2>
                            </div>
                            <div class="table-responsive">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nome da Categoria</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($categorias as $cat): ?>
                                            <tr>
                                                <td>#<?= str_pad($cat['id'], 3, '0', STR_PAD_LEFT) ?></td>
                                                <td><strong><?= htmlspecialchars($cat['nome']) ?></strong></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script src="js/main.js?v=<?= time(); ?>"></script>
</body>
</html>