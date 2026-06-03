<?php
require_once __DIR__ . '/../Models/dashboardmodel.php';

class DashboardController {
    private $model;

    public function __construct() {
        $this->model = new DashboardModel();
    }

    public function index() {
        return [
            'cards'    => $this->model->getCardsResumo(),
            'produtos' => $this->model->getProdutosRecentes()
        ];
    }

    public function exportarCSV() {
        $produtos = $this->model->getProdutosParaExportacao();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=relatorio_estoque_' . date('Y-m-d') . '.csv');

        $saida = fopen('php://output', 'w');
        
        fprintf($saida, chr(0xEF).chr(0xBB).chr(0xBF)); 
        fputcsv($saida, ['SKU', 'Produto', 'Categoria', 'Preço de Custo', 'Quantidade', 'Status'], ';');

        foreach ($produtos as $p) {
            $status = $p['quantidade_atual'] <= 0 ? 'Esgotado' : ($p['quantidade_atual'] <= 5 ? 'Baixo Estoque' : 'Em Estoque');
            $precoFormatado = 'R$ ' . number_format($p['preco_custo'], 2, ',', '.');
            
            fputcsv($saida, [
                $p['codigo_sku'], 
                $p['nome'], 
                $p['categoria'], 
                $precoFormatado, 
                $p['quantidade_atual'], 
                $status
            ], ';');
        }
        
        fclose($saida);
        exit(); 
    }
}