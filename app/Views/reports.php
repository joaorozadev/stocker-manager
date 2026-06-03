<!DOCTYPE html>
<html lang="pt-br" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stocker Manager - Relatórios</title>
    <link rel="stylesheet" href="css/style.css?v=<?= time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="dashboard-body">

    <div class="app-container">
        
        <?php include __DIR__ . '/partials/sidebar.php'; ?>

        <main class="main-content">
            
            <header class="topbar">
                <div class="page-title">
                    <h1>Análise e Relatórios</h1>
                </div>
                <div class="topbar-actions" style="display: flex; gap: 0.8rem; align-items: center;">   
                    
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class="fa-solid fa-calendar-days" style="position: absolute; left: 12px; color: var(--text-muted); pointer-events: none; z-index: 1;"></i>
                        
                        <select id="filtroAnoGlobal" class="sort-select" style="padding-left: 36px; min-width: 140px; cursor: pointer; margin: 0;">
                            <option value="geral" <?= $dadosRelatorio['filtros']['anoAtual'] === 'geral' ? 'selected' : '' ?>>Visão Geral</option>
                            <?php foreach ($dadosRelatorio['anosDisponiveis'] as $anoItem): ?>
                                <option value="<?= $anoItem ?>" <?= $dadosRelatorio['filtros']['anoAtual'] == $anoItem ? 'selected' : '' ?>>
                                    Ano: <?= $anoItem ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button class="btn-primary" onclick="window.location.href='index.php?action=exportar_relatorio'" style="margin: 0;">
                        <i class="fa-solid fa-download"></i> Exportar Dados
                    </button>
                    
                </div>
            </header>

            <div class="charts-grid" 
                 data-linha="<?= htmlspecialchars(json_encode($dadosRelatorio['graficoLinha'] ?? ['entradas'=>[], 'saidas'=>[]]), ENT_QUOTES, 'UTF-8') ?>"
                 data-rosca="<?= htmlspecialchars(json_encode($dadosRelatorio['graficoRosca'] ?? ['labels'=>[], 'valores'=>[]]), ENT_QUOTES, 'UTF-8') ?>">
                
                <div class="chart-card main-chart">
                    <div class="chart-header">
                        <h2>Fluxo de Movimentações (Entradas vs Saídas)</h2>
                        
                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                            <input type="month" id="filtroMes" 
                                   value="<?= $dadosRelatorio['filtros']['mesAtual'] ?>" 
                                   style="<?= ($dadosRelatorio['filtros']['periodoAtual'] === 'anual' || $dadosRelatorio['filtros']['anoAtual'] === 'geral') ? 'display: none;' : 'padding: 0.4rem; border-radius: 0.3rem; border: 1px solid #334155; background: #0f172a; color: #f8fafc;' ?>">
                            
                            <select id="filtroPeriodo" class="sort-select" <?= $dadosRelatorio['filtros']['anoAtual'] === 'geral' ? 'disabled title="Selecione um ano específico para habilitar a visão mensal"' : '' ?>>
                                <option value="anual" <?= ($dadosRelatorio['filtros']['periodoAtual'] === 'anual' || $dadosRelatorio['filtros']['anoAtual'] === 'geral') ? 'selected' : '' ?>>Anual</option>
                                
                                <?php if ($dadosRelatorio['filtros']['anoAtual'] !== 'geral'): ?>
                                    <option value="mensal" <?= $dadosRelatorio['filtros']['periodoAtual'] === 'mensal' ? 'selected' : '' ?>>Mensal</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="canvas-container" style="position: relative; height: 300px; width: 100%;">
                        <canvas id="flowChart"></canvas>
                    </div>
                </div>

                <div class="chart-card side-chart">
                    <div class="chart-header">
                        <h2>Valor por Categoria <?= $dadosRelatorio['filtros']['anoAtual'] === 'geral' ? '(Visão Geral)' : '- Ano ' . htmlspecialchars($dadosRelatorio['filtros']['anoAtual']) ?></h2>
                    </div>
                    <div class="canvas-container" style="position: relative; height: 300px; width: 100%;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>

            </div> 

            <section class="table-section" style="margin-top: 1.5rem;">
                <div class="table-header">
                    <h2>Produtos com Maior Giro <?= $dadosRelatorio['filtros']['anoAtual'] === 'geral' ? '(Visão Geral)' : '- Ano ' . htmlspecialchars($dadosRelatorio['filtros']['anoAtual']) ?></h2>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Produto</th>
                                <th>Categoria</th>
                                <th>Entradas</th>
                                <th>Saídas</th>
                                <th>Status Atual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($dadosRelatorio['maiorGiro'])): ?>
                                <tr><td colspan="6" style="text-align: center; padding: 2rem;">Nenhuma movimentação registrada.</td></tr>
                            <?php else: ?>
                                <?php foreach ($dadosRelatorio['maiorGiro'] as $p): ?>
                                    <?php
                                        $qtd = (int) $p['quantidade_atual'];
                                        if ($qtd <= 0) {
                                            $badge = '<span class="badge badge-danger">Esgotado</span>';
                                        } elseif ($qtd <= 5) {
                                            $badge = '<span class="badge badge-warning">Atenção</span>';
                                        } else {
                                            $badge = '<span class="badge badge-success">Estoque Saudável</span>';
                                        }
                                    ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($p['codigo_sku']) ?></strong></td>
                                        <td><?= htmlspecialchars($p['nome']) ?></td>
                                        <td><?= htmlspecialchars($p['categoria'] ?? 'Geral') ?></td>
                                        <td><span class="text-success" style="font-weight: 700;"><i class="fa-solid fa-arrow-down"></i> <?= $p['total_entradas'] ?> un.</span></td>
                                        <td><span class="text-danger" style="font-weight: 700; color: #ef4444;"><i class="fa-solid fa-arrow-up"></i> <?= $p['total_saidas'] ?> un.</span></td>
                                        <td><?= $badge ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script src="js/main.js?v=<?= time(); ?>"></script>
</body>
</html>