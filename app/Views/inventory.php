<!DOCTYPE html>
<html lang="pt-br" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stocker Manager - Inventário</title>
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
                    <h1>Inventário de Produtos</h1>
                </div>
                
                <div class="topbar-actions" style="display: flex; gap: 1rem; align-items: center; flex-wrap: nowrap;">
                    <div style="position: relative; width: 280px; max-width: 100%;">
                        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #64748b; pointer-events: none;"></i>
                        <input type="text" id="searchInput" placeholder="Pesquisar no catálogo..." style="padding-left: 2.5rem; margin: 0; width: 100%;">
                    </div>
                    
                    <button class="btn-primary" id="btnNovoProduto" style="margin: 0; white-space: nowrap;">
                        <i class="fa-solid fa-plus"></i> Novo Produto
                    </button>
                </div>
            </header>

            <div class="inventory-container">
                
                <?php
                    $contagemCategorias = [];
                    foreach ($listaInventario as $prod) {
                        $nomeCat = $prod['categoria_nome'] ?? 'Sem Categoria';
                        if (!isset($contagemCategorias[$nomeCat])) {
                            $contagemCategorias[$nomeCat] = 0;
                        }
                        $contagemCategorias[$nomeCat]++;
                    }
                ?>

                <aside class="inventory-filters">
                    <div class="filter-group">
                        <h3 class="filter-group-header">Categorias</h3>
                        <ul class="category-list">
                            <li>
                                <button class="cat-btn active">
                                    Todos <span class="cat-count" style="color: #64748b; font-size: 0.85em; margin-left: 4px;">(<?= count($listaInventario ?? []) ?>)</span>
                                </button>
                            </li>
                            
                            <?php if (!empty($listaCategorias)): ?>
                                <?php foreach ($listaCategorias as $cat): ?>
                                    <?php $qtdCat = $contagemCategorias[$cat['nome']] ?? 0; ?>
                                    <li>
                                        <button class="cat-btn">
                                            <?= htmlspecialchars($cat['nome']) ?> 
                                            <span class="cat-count" style="color: #64748b; font-size: 0.85em; margin-left: 4px;">(<?= $qtdCat ?>)</span>
                                        </button>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="filter-group">
                        <h3 class="filter-group-header">Faixa de Preço</h3>
                        <div class="price-range">
                            <input type="range" min="0" max="10000" step="100" value="10000" id="priceRange">
                            <div class="price-labels">
                                <span>R$ 0</span>
                                <span>R$ 10.000+</span>
                            </div>
                        </div>
                    </div>

                    <div class="filter-group">
                        <h3 class="filter-group-header">Status</h3>
                        <div class="filter-tags-container" id="statusFilterTags">
                            <span class="tag active" data-status="todos">Todos os Itens</span>
                            <span class="tag" data-status="stock">Em Estoque</span>
                            <span class="tag" data-status="out">Esgotado</span>
                        </div>
                    </div>

                    <button id="btnLimparFiltros" class="btn-secondary w-full" style="margin-top: 1rem; width: 100%;">Limpar Filtros</button>
                </aside>

                <section class="inventory-grid-wrapper">
                    <div class="grid-controls">
                        <span>Mostrando <strong><?= count($listaInventario ?? []) ?></strong> produtos</span>
                        <select class="sort-select" id="sortSelect">
                            <option value="recentes">Mais recentes</option>
                            <option value="preco_asc">Preço: Menor para Maior</option>
                            <option value="preco_desc">Preço: Maior para Menor</option>
                            <option value="estoque_asc">Estoque: Menor para Maior</option>
                            <option value="estoque_desc">Estoque: Maior para Menor</option>
                        </select>
                    </div>

                    <div class="product-grid" id="productGrid">
                        
                        <?php if (empty($listaInventario)): ?>
                            <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--text-muted);">
                                <i class="fa-solid fa-box-open" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                                <h3>Nenhum produto cadastrado</h3>
                                <p>Clique em "Novo Produto" para começar a abastecer seu estoque.</p>
                            </div>
                        <?php else: ?>
                            
                            <?php foreach ($listaInventario as $p): ?>
                                <?php
                                    $qtd = (int) $p['quantidade_atual'];
                                    if ($qtd <= 0) {
                                        $badgeClass = 'out';
                                        $badgeText = 'Esgotado';
                                    } elseif ($qtd <= 5) {
                                        $badgeClass = 'warning';
                                        $badgeText = $qtd . ' restando';
                                    } else {
                                        $badgeClass = 'stock';
                                        $badgeText = 'Em Estoque';
                                    }

                                    $catLower = strtolower($p['categoria_nome'] ?? '');
                                    $icon = 'fa-box'; 
                                    
                                    if (strpos($catLower, 'vídeo') !== false || strpos($catLower, 'video') !== false) {
                                        $icon = 'fa-video';
                                    } elseif (strpos($catLower, 'processador') !== false || strpos($catLower, 'placa-mãe') !== false) {
                                        $icon = 'fa-microchip';
                                    } elseif (strpos($catLower, 'memória') !== false || strpos($catLower, 'ram') !== false) {
                                        $icon = 'fa-memory';
                                    } elseif (strpos($catLower, 'armazenamento') !== false || strpos($catLower, 'ssd') !== false || strpos($catLower, 'hd') !== false) {
                                        $icon = 'fa-hard-drive';
                                    }
                                ?>
                                
                                <div class="product-card" 
                                    data-id="<?= $p['id'] ?>" 
                                    data-nome="<?= strtolower(htmlspecialchars($p['nome'])) ?>" 
                                    data-categoria="<?= htmlspecialchars($p['categoria_nome'] ?? 'Sem Categoria') ?>" 
                                    data-preco="<?= $p['preco_custo'] ?>" 
                                    data-status="<?= $badgeClass ?>"
                                    data-quantidade="<?= $qtd ?>">
                                     
                                    <div class="product-badge <?= $badgeClass ?>"><?= $badgeText ?></div>
                                    <div class="product-image"><i class="fa-solid <?= $icon ?>"></i></div>
                                    
                                    <div class="product-info">
                                        <span class="prod-cat"><?= htmlspecialchars($p['categoria_nome'] ?? 'Sem Categoria') ?></span>
                                        <h4 class="prod-name" style="margin-bottom: 0.2rem;"><?= htmlspecialchars($p['nome']) ?></h4>
                                        
                                        <div class="prod-meta" style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 0.8rem; display: flex; gap: 0.5rem; align-items: center;">
                                            <span title="Código SKU"><i class="fa-solid fa-barcode"></i> <?= htmlspecialchars($p['codigo_sku']) ?></span>
                                            <span style="color: #475569;">|</span>
                                            <span style="color: #cbd5e1;"><i class="fa-solid fa-boxes-stacked"></i> <strong><?= $qtd ?> un.</strong></span>
                                        </div>

                                        <div class="prod-footer" style="display: flex; justify-content: space-between; align-items: center;">
                                            <span class="prod-price" style="font-weight: bold;">R$ <?= number_format($p['preco_custo'], 2, ',', '.') ?></span>
                                            
                                            <div class="card-actions" style="display: flex; gap: 0.4rem;">
                                                <button class="btn-add-mini btn-repor-estoque" title="Entrada Rápida" 
                                                        style="background-color: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16,185,129,0.3);"
                                                        data-id="<?= $p['id'] ?>" 
                                                        data-nome="<?= htmlspecialchars($p['nome']) ?>">
                                                    <i class="fa-solid fa-arrow-down"></i>
                                                </button>
                                                
                                                <button class="btn-add-mini btn-editar-produto" title="Editar Produto"
                                                        data-id="<?= $p['id'] ?>" 
                                                        data-nome="<?= htmlspecialchars($p['nome']) ?>" 
                                                        data-categoria="<?= $p['categoria_id'] ?>" 
                                                        data-preco="<?= $p['preco_custo'] ?>">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                    </div>
                </section>
            </div>
        </main>
    </div>

    <?php include __DIR__ . '/partials/modal_produto.php'; ?>
    <?php include __DIR__ . '/partials/modal_editar_produto.php'; ?>
    <?php include __DIR__ . '/partials/modal_reposicao.php'; ?> 

    <script src="js/main.js?v=<?= time(); ?>"></script>
</body>
</html>