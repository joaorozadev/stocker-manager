<?php
require_once __DIR__ . '/../../Models/produtomodel.php';
$modalProdModel = new ProdutoModel();
$listaCategorias = $modalProdModel->getCategorias();
$listaFornecedores = $modalProdModel->getFornecedores();
?>

<div class="modal-overlay hidden" id="modalNovoProduto">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Cadastrar Novo Produto</h2>
            <button class="close-modal" id="btnCloseModal"><i class="fa-solid fa-xmark"></i></button>
        </div>
        
        <div class="modal-body">
            <form id="formNovoProduto" method="POST" action="index.php?action=salvar_produto">
                
                <div class="form-group">
                    <label for="prodNome">Nome do Produto</label>
                    <input type="text" id="prodNome" name="prodNome" placeholder="Ex: Placa Mãe B550M Aorus Elite" required>
                </div>

                <div class="form-row split-2">
                    <div class="form-group">
                        <label for="prodCategoria">Categoria</label>
                        <select id="prodCategoria" name="prodCategoria" required>
                            <option value="">Selecione...</option>
                            <?php foreach ($listaCategorias as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="prodFornecedor">Fornecedor</label>
                        <select id="prodFornecedor" name="prodFornecedor">
                            <option value="">Selecione (Opcional)...</option>
                            <?php foreach ($listaFornecedores as $forn): ?>
                                <option value="<?= $forn['id'] ?>"><?= htmlspecialchars($forn['nome_fantasia']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row split-2">
                    <div class="form-group">
                        <label for="prodPreco">Preço de Custo (R$)</label>
                        <input type="number" step="0.01" id="prodPreco" name="prodPreco" placeholder="0,00" required>
                    </div>
                    <div class="form-group">
                        <label for="prodQtd">Estoque Inicial</label>
                        <input type="number" id="prodQtd" name="prodQtd" placeholder="Ex: 10" min="0" required>
                    </div>
                </div>

            </form>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="btnCancelModal">Cancelar</button>
            <button type="submit" class="btn-primary" id="btnSaveProduto" form="formNovoProduto">
                <span class="btn-text">Salvar Produto</span>
            </button>
        </div>
    </div>
</div>