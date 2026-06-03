<!DOCTYPE html>
<html lang="pt-br" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stocker Manager - Dashboard</title>
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
                    <h1>Dashboard</h1>
                </div>
                <div class="topbar-actions">
                    <button class="btn-icon"><i class="fa-regular fa-bell"></i></button>
                        <button onclick="window.location.href='index.php?action=exportar_csv'" class="btn-secondary">
                            <i class="fa-solid fa-file-csv"></i> Produtos csv
                        </button>
                    <button class="btn-primary" id="btnNovoProduto"><i class="fa-solid fa-plus"></i> Novo Produto</button>
                </div>
            </header>

            <section class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-icon" style="background: #e0e7ff; color: #4f46e5;"><i class="fa-solid fa-dollar-sign"></i></div>
                    <div class="kpi-details">
                        <h3>Valor em Estoque</h3>
                        <p>R$ <?= number_format((float)($cards['valor_stock'] ?? 0), 2, ',', '.') ?></p>
                    </div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-icon" style="background: #dcfce7; color: #16a34a;"><i class="fa-solid fa-box"></i></div>
                    <div class="kpi-details">
                        <h3>Total de Produtos</h3>
                        <p><?= $cards['total_itens'] ?? 0 ?> un.</p>
                    </div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-icon" style="background: #fef3c7; color: #d97706;"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <div class="kpi-details">
                        <h3>Estoque Baixo</h3>
                        <p><?= $cards['baixo_stock'] ?? 0 ?> itens</p>
                        <?php if(($cards['baixo_stock'] ?? 0) > 0): ?>
                            <span class="trend down"><i class="fa-solid fa-arrow-down"></i> Atenção</span>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <section class="table-section">
                <div class="table-header">
                    <div>
                        <h2>Produtos com Menor Estoque</h2>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.2rem;">Exibindo os 5 itens que exigem maior atenção no momento</p>
                    </div>
                    <div class="table-search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="searchInput" placeholder="Buscar produto...">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Código SKU</th>
                                <th>Produto</th>
                                <th>Categoria</th>
                                <th>Quantidade</th>
                                <th>Status</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php if (empty($produtos)): ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 2rem;">Nenhum produto cadastrado no sistema.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($produtos as $p): ?>
                                    <?php
                                        $qtd = (int) $p['quantidade_atual'];
                                        if ($qtd <= 0) {
                                            $badgeClass = 'badge-danger';
                                            $statusText = 'Esgotado';
                                        } elseif ($qtd <= 5) {
                                            $badgeClass = 'badge-warning';
                                            $statusText = 'Baixo Estoque';
                                        } else {
                                            $badgeClass = 'badge-success';
                                            $statusText = 'Em Estoque';
                                        }
                                    ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($p['codigo_sku']) ?></strong></td>
                                            <td><i class="fa-solid fa-microchip" style="margin-right: 8px; color: var(--text-muted);"></i> <?= htmlspecialchars($p['nome']) ?></td>
                                            <td><?= htmlspecialchars($p['categoria_nome'] ?? 'Sem Categoria') ?></td>
                                            <td><?= $qtd ?> un.</td>
                                            <td><span class="badge <?= $badgeClass ?>"><?= $statusText ?></span></td>
                                            
                                            <td class="action-cell">
                                                <button class="btn-action toggle-dropdown"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                                
                                                <div class="dropdown-menu hidden">
                                                    <a href="#" class="dropdown-item text-success btn-repor-estoque" 
                                                        data-id="<?= $p['id'] ?>" 
                                                        data-nome="<?= htmlspecialchars($p['nome']) ?>">
                                                        <i class="fa-solid fa-arrow-down"></i> Repor Estoque
                                                    </a>
                                                    <a href="#" class="dropdown-item text-primary btn-editar-produto" 
                                                        data-id="<?= $p['id'] ?? '' ?>" 
                                                        data-nome="<?= htmlspecialchars($p['nome'] ?? '') ?>" 
                                                        data-categoria="<?= $p['categoria_id'] ?? '' ?>" 
                                                        data-preco="<?= $p['preco_custo'] ?? '0' ?>">
                                                        <i class="fa-solid fa-pen"></i> Editar Produto
                                                    </a>
                                                    <a href="index.php?page=transactions&search=<?= urlencode($p['codigo_sku']) ?>" class="dropdown-item text-muted">
                                                        <i class="fa-solid fa-clock-rotate-left"></i> Ver Histórico
                                                    </a>
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
    </div>

    <?php include __DIR__ . '/partials/modal_produto.php'; ?>
    <?php include __DIR__ . '/partials/modal_editar_produto.php'; ?>
    <?php include __DIR__ . '/partials/modal_reposicao.php'; ?> 

    <script src="js/main.js?v=<?= time(); ?>"></script>
</body>
</html>