<?php
require_once __DIR__ . '/../Models/transactionmodel.php';

class TransactionController {
    private $model;

    public function __construct() {
        $this->model = new TransactionModel();
    }

    public function index() {
        $tipo = $_GET['tipo'] ?? 'todos';
        $dataInicio = $_GET['data_inicio'] ?? '';
        $dataFim = $_GET['data_fim'] ?? '';
        $busca = $_GET['search'] ?? '';

        $limite = isset($_GET['limite']) ? (int)$_GET['limite'] : 10;
        $paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        if ($paginaAtual < 1) $paginaAtual = 1;

        $offset = ($paginaAtual - 1) * $limite;

        $movimentacoes = $this->model->listarMovimentacoes($tipo, $dataInicio, $dataFim, $busca, $limite, $offset);

        $totalRegistros = $this->model->getTotalMovimentacoes($tipo, $dataInicio, $dataFim, $busca);
        $totalPaginas = ceil($totalRegistros / $limite);

        $produtosDropdown = $this->model->getProdutosParaSelect();

        return [
            'movimentacoes' => $movimentacoes,
            'produtos' => $produtosDropdown, 
            'filtros' => [
                'tipo' => $tipo,
                'data_inicio' => $dataInicio,
                'data_fim' => $dataFim,
                'search' => $busca
            ],
            'paginacao' => [
                'atual' => $paginaAtual,
                'total' => $totalPaginas,
                'limite' => $limite,
                'totalRegistros' => $totalRegistros
            ]
        ];
    }

    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            if (!isset($_SESSION['user_id'])) {
                header("Location: index.php?page=login");
                exit();
            }

            $usuario_id = $_SESSION['user_id']; 
            $produto_id = $_POST['transProduto'] ?? '';
            $tipo = $_POST['transTipo'] ?? '';
            $quantidade = (int)($_POST['transQtd'] ?? 0);
            $motivo = $_POST['transMotivo'] ?? '';
            
            $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=transactions';
            $url_parts = parse_url($referer);
            parse_str($url_parts['query'] ?? '', $query_params);

            if (!empty($produto_id) && !empty($tipo) && $quantidade > 0 && !empty($motivo)) {
                
                if ($tipo === 'saida') {
                    $produto = $this->model->getProdutoById($produto_id); 
                    
                    if ($produto['quantidade_atual'] < $quantidade) {
                        $query_params['erro'] = 'estoque_insuficiente';
                        unset($query_params['mensagem']); 
                        
                        $nova_url = ($url_parts['path'] ?? 'index.php') . '?' . http_build_query($query_params);
                        header("Location: " . $nova_url);
                        exit();
                    }
                }

                $this->model->registrarMovimento($produto_id, $usuario_id, $tipo, $quantidade, $motivo);
            }
            $query_params['mensagem'] = 'sucesso';
            unset($query_params['erro']); 
            
            $nova_url = ($url_parts['path'] ?? 'index.php') . '?' . http_build_query($query_params);
            header("Location: " . $nova_url);
            exit();
        }
    }

    public function exportarCSV() {     
        $movimentacoes = $this->model->getHistoricoExportacao();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=historico_movimentacoes_' . date('Y-m-d') . '.csv');

        $saida = fopen('php://output', 'w');
        
        fprintf($saida, chr(0xEF).chr(0xBB).chr(0xBF)); 
        
        fputcsv($saida, ['Data e Hora', 'SKU', 'Produto', 'Tipo', 'Quantidade', 'Motivo / Observação', 'Usuário Responsável'], ';');

        foreach ($movimentacoes as $m) {
            $dataFormated = date('d/m/Y H:i', strtotime($m['data_movimento']));
            $tipoFormated = (strtolower($m['tipo']) === 'entrada') ? 'Entrada (+)' : 'Saída (-)';
            $usuario = $m['usuario_nome'] ? $m['usuario_nome'] : 'Sistema';
            
            fputcsv($saida, [
                $dataFormated,
                $m['codigo_sku'],
                $m['produto_nome'],
                $tipoFormated,
                $m['quantidade'] . ' un.',
                $m['motivo'],
                $usuario
            ], ';');
        }
        fclose($saida);
        exit(); 
    }
}