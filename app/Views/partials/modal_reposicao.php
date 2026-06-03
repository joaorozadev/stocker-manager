<div class="modal-overlay hidden" id="modalReporEstoque">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Repor Estoque</h2>
            <button class="close-modal" id="btnCloseReporModal"><i class="fa-solid fa-xmark"></i></button>
        </div>
        
        <div class="modal-body">
            <form id="formReporEstoque" method="POST" action="index.php?action=salvar_movimento">
                
                <input type="hidden" id="reporProdId" name="transProduto">
                <input type="hidden" name="transTipo" value="entrada">
                
                <div class="form-group">
                    <label>Produto Selecionado</label>
                    <input type="text" id="reporProdNome" class="input-readonly" readonly>
                </div>

                <div class="form-row split-2">
                    <div class="form-group" style="flex: 0.4;">
                        <label for="reporQtd">Qtd. a Adicionar</label>
                        <input type="number" id="reporQtd" name="transQtd" min="1" placeholder="Ex: 10" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="reporMotivo">Motivo / Observação</label>
                        <input type="text" id="reporMotivo" name="transMotivo" placeholder="Ex: Compra do fornecedor..." required>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="btnCancelReporModal">Cancelar</button>
            <button type="submit" class="btn-success" form="formReporEstoque" style="background-color: #16a34a; color: white;">
                <span class="btn-text">Confirmar Entrada</span>
            </button>
        </div>
    </div>
</div>