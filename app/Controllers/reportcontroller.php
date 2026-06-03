<?php
require_once __DIR__ . '/../Models/reportmodel.php';

class ReportController {
    private $model;

    public function __construct() {
        $this->model = new ReportModel();
    }

public function index() {
        $periodo = $_GET['periodo'] ?? 'anual';
        $mesFiltro = $_GET['mes'] ?? date('Y-m');
        $anoFiltro = $_GET['ano'] ?? 'geral'; 

        $labelsLinha = []; $entradasLinha = []; $saidasLinha = [];

        if ($anoFiltro === 'geral') {
            $fluxoRaw = $this->model->getFluxoAnualGlobal();
            $anosSet = [];
            foreach ($fluxoRaw as $row) { $anosSet[(int)$row['ano']] = true; }
            $anosUnicos = array_keys($anosSet);
            sort($anosUnicos);
            
            $entradasArr = array_fill_keys($anosUnicos, 0);
            $saidasArr = array_fill_keys($anosUnicos, 0);
            
            foreach ($fluxoRaw as $row) {
                $ano = (int)$row['ano'];
                if (strtolower($row['tipo']) === 'entrada') {
                    $entradasArr[$ano] = (int)$row['total_itens'];
                } else {
                    $saidasArr[$ano] = (int)$row['total_itens'];
                }
            }
            $labelsLinha = array_map('strval', $anosUnicos);
            $entradasLinha = array_values($entradasArr);
            $saidasLinha = array_values($saidasArr);
        }
        elseif ($periodo === 'anual') {
            $fluxoRaw = $this->model->getFluxoMensal($anoFiltro);
            $entradasArr = array_fill(1, 12, 0);
            $saidasArr = array_fill(1, 12, 0);

            foreach ($fluxoRaw as $row) {
                $mes = (int)$row['mes'];
                if (strtolower($row['tipo']) === 'entrada') {
                    $entradasArr[$mes] = (int)$row['total_itens'];
                } else {
                    $saidasArr[$mes] = (int)$row['total_itens'];
                }
            }
            $labelsLinha = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
            $entradasLinha = array_values($entradasArr);
            $saidasLinha = array_values($saidasArr);
        } 
        else {
            $partes = explode('-', $mesFiltro);
            $ano = (int)$partes[0]; $mes = (int)$partes[1];
            $diasNoMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
            
            $fluxoRaw = $this->model->getFluxoDiario($ano, $mes);
            $entradasArr = array_fill(1, $diasNoMes, 0);
            $saidasArr = array_fill(1, $diasNoMes, 0);

            foreach ($fluxoRaw as $row) {
                $dia = (int)$row['dia'];
                if (strtolower($row['tipo']) === 'entrada') {
                    $entradasArr[$dia] = (int)$row['total_itens'];
                } else {
                    $saidasArr[$dia] = (int)$row['total_itens'];
                }
            }
            for ($i = 1; $i <= $diasNoMes; $i++) {
                $labelsLinha[] = str_pad($i, 2, '0', STR_PAD_LEFT) . '/' . str_pad($mes, 2, '0', STR_PAD_LEFT);
            }
            $entradasLinha = array_values($entradasArr);
            $saidasLinha = array_values($saidasArr);
        }

        $catsRaw = $this->model->getValorPorCategoria($anoFiltro);
        $catLabels = []; $catValores = [];
        foreach ($catsRaw as $cat) {
            $catLabels[] = $cat['categoria'];
            $catValores[] = (float)$cat['valor_total'];
        }

        return [
            'filtros' => [
                'periodoAtual' => $periodo,
                'mesAtual'     => $mesFiltro,
                'anoAtual'     => $anoFiltro
            ],
            'anosDisponiveis' => $this->model->getAnosDisponiveis(),
            'graficoLinha' => [
                'labels'   => $labelsLinha,
                'entradas' => $entradasLinha,
                'saidas'   => $saidasLinha
            ],
            'graficoRosca' => [
                'labels'  => $catLabels,
                'valores' => $catValores
            ],
            'maiorGiro' => $this->model->getProdutosMaiorGiro(5, $anoFiltro) 
        ];
    }
    public function exportarCSV() {
        $ano = $_GET['ano'] ?? 'geral';
        $dados = $this->model->getProdutosMaiorGiro(100, $ano); 

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=relatorio_produtos_giro_' . date('Y-m-d') . '.csv');

        $saida = fopen('php://output', 'w');
        fprintf($saida, chr(0xEF).chr(0xBB).chr(0xBF)); 
        
        fputcsv($saida, ['Codigo SKU', 'Produto', 'Categoria', 'Entradas', 'Saídas', 'Estoque Atual'], ';');

        foreach ($dados as $p) {
            fputcsv($saida, [
                $p['codigo_sku'],
                $p['nome'],
                $p['categoria'] ?? 'Sem Categoria',
                '+' . $p['total_entradas'],
                '-' . $p['total_saidas'],
                $p['quantidade_atual']
            ], ';');
        }
        fclose($saida);
        exit();
    }
}