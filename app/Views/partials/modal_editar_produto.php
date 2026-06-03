<?php
require_once __DIR__ . '/../../Models/produtomodel.php';
$editProdModel = new ProdutoModel();
$listaCategoriasEdit = $editProdModel->getCategorias();
?>

<div class="modal-overlay hidden" id="modalEditarProduto">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Editar Produto</h2>
            <button class="close-modal" id="btnCloseEditModal"><i class="fa-solid fa-xmark"></i></button>
        </div>
        
        <div class="modal-body">
            <form id="formEditarProduto" method="POST" action="index.php?action=editar_produto">
                <input type="hidden" id="editProdId" name="editProdId">
                
                <div class="form-group">
                    <label for="editProdNome">Nome do Produto</label>
                    <input type="text" id="editProdNome" name="editProdNome" required>
                </div>

                <div class="form-row split-2">
                    <div class="form-group">
                        <label for="editProdCategoria">Categoria</label>
                        <select id="editProdCategoria" name="editProdCategoria" required>
                            <option value="">Selecione...</option>
                            <?php foreach ($listaCategoriasEdit as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editProdPreco">Preço de Custo (R$)</label>
                        <input type="number" step="0.01" id="editProdPreco" name="editProdPreco" required>
                    </div>
                </div>
            </form>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="btnCancelEditModal">Cancelar</button>
            <button type="submit" class="btn-primary" form="formEditarProduto">
                <span class="btn-text">Salvar Alterações</span>
            </button>
        </div>
    </div>
</div>