<!DOCTYPE html>
<html lang="pt-br" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stocker Manager - Movimentações</title>
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
                    <h1>Histórico de Movimentações</h1>
                </div>
                <div class="topbar-actions">
                    <button onclick="window.location.href='index.php?action=exportar_transacoes_csv'" class="btn-secondary">
                        <i class="fa-solid fa-file-csv"></i> Gerar Relatório
                    </button>
                    <button class="btn-primary" id="btnNovaMovimentacao"><i class="fa-solid fa-right-left"></i> Registrar Movimento</button>
                </div>
            </header>

            <form id="filterTransactionsForm" class="filter-bar-horizontal" method="GET" action="index.php" style="align-items: center;">
                <input type="hidden" name="page" value="transactions">
                <input type="hidden" name="pagina" id="inputPaginaAtual" value="<?= $paginacao['atual'] ?>">

                <div class="filter-item" style="flex: 1.2; min-width: 200px;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" id="transactionSearch" placeholder="Buscar nesta tela..." value="<?= htmlspecialchars($filtros['search'] ?? '') ?>">
                </div>
                
                <div class="filter-item-select" style="flex: 1; min-width: 150px;">
                    <select name="tipo" id="filtroTipoTransacao">
                        <option value="todos" <?= ($filtros['tipo'] ?? '') === 'todos' ? 'selected' : '' ?>>Todos os Tipos</option>
                        <option value="entrada" <?= ($filtros['tipo'] ?? '') === 'entrada' ? 'selected' : '' ?>>Apenas Entradas</option>
                        <option value="saida" <?= ($filtros['tipo'] ?? '') === 'saida' ? 'selected' : '' ?>>Apenas Saídas</option>
                    </select>
                </div>
                
                <div class="filter-item-select" style="flex: 2; display: flex; gap: 0.8rem; align-items: center; min-width: 320px;">
                    <span style="font-size: 0.85rem; font-weight: 600; color: #64748b; white-space: nowrap;">De:</span>
                    <input type="date" name="data_inicio" id="filtroDataInicio" value="<?= htmlspecialchars($filtros['data_inicio'] ?? '') ?>" style="flex: 1;">
                    
                    <span style="font-size: 0.85rem; font-weight: 600; color: #64748b; white-space: nowrap;">Até:</span>
                    <input type="date" name="data_fim" id="filtroDataFim" value="<?= htmlspecialchars($filtros['data_fim'] ?? '') ?>" style="flex: 1;">
                </div>

                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <button type="submit" class="btn-primary" style="padding: 0.8rem 1.5rem;">Filtrar</button>
                    
                    <?php if(!empty($filtros['tipo']) && $filtros['tipo'] !== 'todos' || !empty($filtros['data_inicio']) || !empty($filtros['data_fim']) || !empty($filtros['search'])): ?>
                        <a href="index.php?page=transactions" class="btn-secondary" style="padding: 0.8rem; display: flex; align-items: center; justify-content: center; aspect-ratio: 1/1; text-decoration: none; border-radius: 0.5rem;" title="Limpar Filtros">
                            <i class="fa-solid fa-eraser"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>

            <section class="table-section" style="display: flex; flex-direction: column;">
                <div class="table-responsive" style="flex: 1;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Data e Hora</th>
                                <th>Produto</th>
                                <th>Tipo</th>
                                <th>Qtd</th>
                                <th>Motivo / Observação</th>
                                <th>Usuário Responsável</th>
                            </tr>
                        </thead>
                        <tbody id="transactionTableBody">
                            <?php if (empty($movimentacoes)): ?>
                                <tr><td colspan="6" style="text-align:center; padding: 2rem;">Nenhuma movimentação encontrada para estes filtros.</td></tr>
                            <?php else: ?>
                                <?php foreach ($movimentacoes as $mov): ?>
                                    <?php 
                                        $dataFmt = date('d/m/Y - H:i', strtotime($mov['data_movimento']));
                                        $isEntrada = ($mov['tipo_movimento'] === 'entrada');
                                        $badgeClass = $isEntrada ? 'badge-success' : 'badge-danger';
                                        $icone = $isEntrada ? 'fa-arrow-down' : 'fa-arrow-up';
                                        $sinalQtd = $isEntrada ? '+ ' : '- ';
                                        $textoTipo = ucfirst($mov['tipo_movimento']);
                                        
                                        $nomeResp = $mov['usuario_nome'] ?? 'Sistema';
                                        $partesResp = explode(' ', trim($nomeResp));
                                        $iniciaisResp = strtoupper(substr($partesResp[0], 0, 1));
                                        if (count($partesResp) > 1) {
                                            $iniciaisResp .= strtoupper(substr($partesResp[1], 0, 1));
                                        }
                                    ?>
                                    <tr>
                                        <td><?= $dataFmt ?></td>
                                        <td><strong><?= htmlspecialchars($mov['codigo_sku']) ?></strong> <br> <?= htmlspecialchars($mov['produto_nome']) ?></td>
                                        <td><span class="badge <?= $badgeClass ?>"><i class="fa-solid <?= $icone ?>"></i> <?= $textoTipo ?></span></td>
                                        <td><strong><?= $sinalQtd . $mov['quantidade'] ?> un.</strong></td>
                                        <td style="color: #64748b;"><?= htmlspecialchars($mov['observacao']) ?></td>
                                        <td>
                                            <div class="user-mini">
                                                <div class="avatar-mini"><?= $iniciaisResp ?></div> 
                                                <?= htmlspecialchars($nomeResp) ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (!empty($movimentacoes) && $paginacao['totalRegistros'] > 0): ?>
                <div class="pagination-footer" style="width: 100%;">
                    <div class="pagination-info">
                        <span>Mostrando</span>
                        <select name="limite" id="limitePagina" class="sort-select" form="filterTransactionsForm">
                            <option value="10" <?= $paginacao['limite'] == 10 ? 'selected' : '' ?>>10</option>
                            <option value="50" <?= $paginacao['limite'] == 50 ? 'selected' : '' ?>>50</option>
                            <option value="100" <?= $paginacao['limite'] == 100 ? 'selected' : '' ?>>100</option>
                            <option value="200" <?= $paginacao['limite'] == 200 ? 'selected' : '' ?>>200</option>
                            <option value="500" <?= $paginacao['limite'] == 500 ? 'selected' : '' ?>>500</option>
                        </select>
                        <span>registros por página de <strong><?= $paginacao['totalRegistros'] ?></strong> no total</span>
                    </div>

                    <div class="pagination-controls">
                        <button type="button" class="btn-page" onclick="mudarPagina(<?= $paginacao['atual'] - 1 ?>)" <?= $paginacao['atual'] <= 1 ? 'disabled' : '' ?>>
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        
                        <?php 
                            $startPage = max(1, $paginacao['atual'] - 1);
                            $endPage = min($paginacao['total'], $paginacao['atual'] + 1);
                            
                            if ($paginacao['atual'] == 1) $endPage = min($paginacao['total'], 3);
                            if ($paginacao['atual'] == $paginacao['total']) $startPage = max(1, $paginacao['total'] - 2);

                            if ($startPage > 1): ?>
                                <button type="button" class="btn-page" onclick="mudarPagina(1)">1</button>
                                <?php if ($startPage > 2): ?>
                                    <button type="button" class="btn-page" disabled>...</button>
                                <?php endif; ?>
                        <?php endif; ?>

                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <button type="button" class="btn-page <?= $paginacao['atual'] == $i ? 'active' : '' ?>" onclick="mudarPagina(<?= $i ?>)">
                                <?= $i ?>
                            </button>
                        <?php endfor; ?>

                        <?php if ($endPage < $paginacao['total']): ?>
                            <?php if ($endPage < $paginacao['total'] - 1): ?>
                                <button type="button" class="btn-page" disabled>...</button>
                            <?php endif; ?>
                            <button type="button" class="btn-page" onclick="mudarPagina(<?= $paginacao['total'] ?>)"><?= $paginacao['total'] ?></button>
                        <?php endif; ?>

                        <button type="button" class="btn-page" onclick="mudarPagina(<?= $paginacao['atual'] + 1 ?>)" <?= $paginacao['atual'] >= $paginacao['total'] ? 'disabled' : '' ?>>
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <?php endif; ?>
                </section>
        </main>
    </div>

    <div class="modal-overlay hidden" id="modalNovaMovimentacao">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h2>Registrar Movimentação</h2>
                <button class="close-modal" id="btnCloseTransModal"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <div class="modal-body">
                <form id="formNovaMovimentacao" method="POST" action="index.php?action=salvar_movimento">

                    <div class="form-group">
                        <label>Tipo de Movimentação</label>
                        <div class="type-toggle">
                            <input type="radio" name="transTipo" id="tipoEntrada" value="entrada" checked>
                            <label for="tipoEntrada" class="btn-entrada"><i class="fa-solid fa-arrow-down"></i> Entrada no Estoque</label>
                            
                            <input type="radio" name="transTipo" id="tipoSaida" value="saida">
                            <label for="tipoSaida" class="btn-saida"><i class="fa-solid fa-arrow-up"></i> Saída de Estoque</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="transProduto">Produto</label>
                        <select id="transProduto" name="transProduto" required>
                            <option value="">Selecione um produto do catálogo...</option>
                            <?php foreach ($listaProdutos as $prod): ?>
                                <option value="<?= $prod['id'] ?>">
                                    <?= htmlspecialchars($prod['codigo_sku']) ?> - <?= htmlspecialchars($prod['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
    
                    <div class="form-group">
                        <label for="transQtd">Quantidade</label>
                        <input type="number" id="transQtd" name="transQtd" placeholder="Ex: 5" min="1" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="transMotivo">Motivo / Observação</label>
                        <input type="text" id="transMotivo" name="transMotivo" placeholder="Ex: Venda, Compra, Defeito, Ajuste..." required>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" id="btnCancelTransModal">Cancelar</button>
                <button type="submit" class="btn-primary" id="btnSaveTrans" form="formNovaMovimentacao">
                    <span class="btn-text">Confirmar Registro</span>
                    <div class="spinner hidden"></div>
                </button>
            </div>
        </div>
    </div>

    <script src="js/main.js?v=<?= time(); ?>"></script>
</body>
</html>