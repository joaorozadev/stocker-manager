<!DOCTYPE html>
<html lang="pt-br" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stocker Manager - Fornecedores</title>
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
                    <h1>Gestão de Fornecedores</h1>
                </div>
                <div class="topbar-actions">
                    <button id="btnNovoFornecedor" class="btn-primary"><i class="fa-solid fa-plus"></i> Novo Fornecedor</button>
                </div>
            </header>

            <section class="table-section" style="margin-top: 1.5rem;">
                <div class="table-header">
                    <h2>Lista de Parceiros Comerciais</h2>
                    <form id="filterSuppliersForm" class="table-search" method="GET" action="index.php">
                        <input type="hidden" name="page" value="suppliers">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" name="search" id="supplierSearch" placeholder="Buscar por nome ou CNPJ..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </form>
                </div>
                
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Empresa (Nome Fantasia)</th>
                                <th>CNPJ</th>
                                <th>Contato Principal</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($fornecedores)): ?>
                                <tr><td colspan="5" style="text-align: center; padding: 2rem;">Nenhum fornecedor encontrado.</td></tr>
                            <?php else: ?>
                                <?php foreach ($fornecedores as $forn): ?>
                                    <tr>
                                        <td>#<?= str_pad($forn['id'], 3, '0', STR_PAD_LEFT) ?></td>
                                        <td><strong><?= htmlspecialchars($forn['nome_fantasia']) ?></strong></td>
                                        <td><?= htmlspecialchars($forn['cnpj']) ?: '<span style="color:var(--text-muted); font-size:0.85rem;">Não informado</span>' ?></td>
                                        <td>
                                            <?php if($forn['email']): ?>
                                                <div><i class="fa-solid fa-envelope" style="color: var(--text-muted); margin-right: 5px;"></i> <?= htmlspecialchars($forn['email']) ?></div>
                                            <?php endif; ?>
                                            <?php if($forn['telefone']): ?>
                                                <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 3px;"><i class="fa-solid fa-phone" style="margin-right: 5px;"></i> <?= htmlspecialchars($forn['telefone']) ?></div>
                                            <?php endif; ?>
                                            <?php if(!$forn['email'] && !$forn['telefone']): ?>
                                                <span style="color:var(--text-muted); font-size:0.85rem;">Sem contatos cadastrados</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div style="display: flex; gap: 0.5rem;">
                                                <button class="btn-icon btn-view-produtos" data-id="<?= $forn['id'] ?>" data-nome="<?= htmlspecialchars($forn['nome_fantasia']) ?>" title="Ver Produtos">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <button class="btn-icon btn-edit-forn" 
                                                    data-id="<?= $forn['id'] ?>" 
                                                    data-nome="<?= htmlspecialchars($forn['nome_fantasia']) ?>" 
                                                    data-cnpj="<?= htmlspecialchars($forn['cnpj']) ?>" 
                                                    data-email="<?= htmlspecialchars($forn['email']) ?>" 
                                                    data-tel="<?= htmlspecialchars($forn['telefone']) ?>" 
                                                    title="Editar">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div> <div class="modal-overlay hidden" id="modalNovoFornecedor">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h2>Cadastrar Fornecedor</h2>
                <button class="close-modal" id="btnCloseFornecedorModal"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <div class="modal-body">
                <form id="formNovoFornecedor" method="POST" action="index.php?action=salvar_fornecedor">
                    <div class="form-group">
                        <label for="fornNome">Nome Fantasia da Empresa *</label>
                        <input type="text" id="fornNome" name="nome_fantasia" placeholder="Ex: KaBuM!" required>
                    </div>

                    <div class="form-group">
                        <label for="fornCnpj">CNPJ</label>
                        <input type="text" id="fornCnpj" name="cnpj" placeholder="Ex: 05.570.714/0001-59">
                    </div>
    
                    <div class="form-row split-2" style="margin-bottom: 0;">
                        <div class="form-group">
                            <label for="fornEmail">E-mail de Contato</label>
                            <input type="email" id="fornEmail" name="email" placeholder="b2b@empresa.com">
                        </div>
                        <div class="form-group">
                            <label for="fornTelefone">Telefone</label>
                            <input type="text" id="fornTelefone" name="telefone" placeholder="(00) 0000-0000">
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" id="btnCancelFornecedorModal">Cancelar</button>
                <button type="submit" class="btn-primary" form="formNovoFornecedor">
                    <span class="btn-text">Salvar Fornecedor</span>
                </button>
            </div>
        </div>
    </div>

    <div class="modal-overlay hidden" id="modalEditarFornecedor">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h2>Editar Fornecedor</h2>
                <button class="close-modal" id="btnCloseEditForn"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <form id="formEditarFornecedor" method="POST" action="index.php?action=editar_fornecedor">
                    <input type="hidden" id="editFornId" name="id">
                    
                    <div class="form-group">
                        <label for="editFornNome">Nome Fantasia da Empresa *</label>
                        <input type="text" id="editFornNome" name="nome_fantasia" required>
                    </div>
                    <div class="form-group">
                        <label for="editFornCnpj">CNPJ</label>
                        <input type="text" id="editFornCnpj" name="cnpj">
                    </div>
                    <div class="form-row split-2" style="margin-bottom: 0;">
                        <div class="form-group">
                            <label for="editFornEmail">E-mail de Contato</label>
                            <input type="email" id="editFornEmail" name="email">
                        </div>
                        <div class="form-group">
                            <label for="editFornTelefone">Telefone</label>
                            <input type="text" id="editFornTelefone" name="telefone">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" id="btnCancelEditForn">Cancelar</button>
                <button type="submit" class="btn-primary" form="formEditarFornecedor">
                    <span class="btn-text">Salvar Alterações</span>
                </button>
            </div>
        </div>
    </div>

    <div class="modal-overlay hidden" id="modalVerProdutos">
        <div class="modal-content" style="max-width: 800px; width: 95%;">
            <div class="modal-header">
                <h2>Produtos de: <span id="tituloFornProdutos" style="color: var(--primary);"></span></h2>
                <button class="close-modal" id="btnCloseViewProd"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body" style="padding: 0;">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="data-table" style="min-width: 100%;">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Produto</th>
                                <th>Estoque</th>
                            </tr>
                        </thead>
                        <tbody id="listaProdutosFornecedor">
                            </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer" style="justify-content: center;">
                <div id="loadingProdutos" class="hidden" style="color: var(--primary);"><i class="fa-solid fa-spinner fa-spin"></i> Carregando...</div>
                <button type="button" class="btn-secondary" id="btnOkViewProd" style="width: 100%;">Fechar</button>
            </div>
        </div>
    </div>

    <script src="js/main.js?v=<?= time(); ?>"></script>
</body>
</html>