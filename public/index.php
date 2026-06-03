<?php
session_start();

// 1. ROTEAMENTO DE AÇÕES DO BACKEND (POST / REDIRECIONAMENTOS)
$action = $_GET['action'] ?? '';

if ($action !== '') {
    $public_actions = ['login', 'register', 'demo'];

    if (!in_array($action, $public_actions) && !isset($_SESSION['user_id'])) {
        if ($action === 'api_produtos_fornecedor') {
            header('HTTP/1.1 401 Unauthorized');
            header('Content-Type: application/json');
            echo json_encode(['erro' => 'Acesso negado. Faça login.']);
            exit();
        }
        header("Location: index.php?page=login");
        exit();
    }

    require_once __DIR__ . '/../app/Controllers/authcontroller.php';
    $auth = new AuthController();

    switch ($action) {
        case 'login':
            $auth->login();
            break;
        case 'register':
            $auth->register();
            break;
        case 'demo':
            $auth->demo();
            break;
        case 'logout':
            $auth->logout();
            break;
        case 'salvar_produto':
            require_once __DIR__ . '/../app/Controllers/produtocontroller.php';
            $prodCtrl = new ProdutoController();
            $prodCtrl->salvar();
            break;
        case 'editar_produto':
            require_once __DIR__ . '/../app/Controllers/produtocontroller.php';
            $prodCtrl = new ProdutoController();
            $prodCtrl->editar();
            break;
        case 'exportar_csv':
            require_once __DIR__ . '/../app/Controllers/dashboardcontroller.php';
            $dashCtrl = new DashboardController();
            $dashCtrl->exportarCSV();
            break;
        case 'salvar_movimento':
            require_once __DIR__ . '/../app/Controllers/transactioncontroller.php';
            $transCtrl = new TransactionController();
            $transCtrl->salvar();
            break;
        case 'exportar_transacoes_csv':
            require_once __DIR__ . '/../app/Controllers/transactioncontroller.php';
            $transCtrl = new TransactionController();
            $transCtrl->exportarCSV();
            break;
        case 'exportar_relatorio':
            require_once __DIR__ . '/../app/Controllers/reportcontroller.php';
            $repCtrl = new ReportController();
            $repCtrl->exportarCSV();
            break;
        case 'salvar_fornecedor':
            require_once __DIR__ . '/../app/Controllers/suppliercontroller.php';
            $supCtrl = new SupplierController();
            $supCtrl->salvar();
            break;
        case 'editar_fornecedor':
            require_once __DIR__ . '/../app/Controllers/suppliercontroller.php';
            $supCtrl = new SupplierController();
            $supCtrl->editar();
            break;
        case 'api_produtos_fornecedor':
            require_once __DIR__ . '/../app/Controllers/suppliercontroller.php';
            $supCtrl = new SupplierController();
            $supCtrl->apiProdutosFornecedor();
            break;
        case 'salvar_categoria':
            require_once __DIR__ . '/../app/Controllers/settingscontroller.php';
            $setCtrl = new SettingsController();
            $setCtrl->salvarCategoria();
            break;
        case 'salvar_usuario':
            require_once __DIR__ . '/../app/Controllers/settingscontroller.php';
            $setCtrl = new SettingsController();
            $setCtrl->salvarUsuario();
            break;
        case 'alterar_senha':
            require_once __DIR__ . '/../app/Controllers/settingscontroller.php';
            $setCtrl = new SettingsController();
            $setCtrl->alterarSenha();
            break;
}
}

// 2. CONFIGURAÇÃO DA PÁGINA ATUAL E PROTEÇÃO DE ROTAS
$default_page = isset($_SESSION['user_id']) ? 'dashboard' : 'login';
$page = $_GET['page'] ?? $default_page;

$public_pages = ['login', 'signup'];

if (!in_array($page, $public_pages) && !isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit();
}

if (in_array($page, $public_pages) && isset($_SESSION['user_id'])) {
    header("Location: index.php?page=dashboard");
    exit();
}

// 3. ROTEADOR DE VIEWS (CARREGA O HTML E OS DADOS)
$allowed_pages = ['login', 'signup', 'dashboard', 'inventory', 'transactions', 'reports', 'suppliers', 'settings'];

if (in_array($page, $allowed_pages)) {
    
    if ($page === 'dashboard') {
        require_once __DIR__ . '/../app/Controllers/dashboardcontroller.php';
        $dashCtrl = new DashboardController();
        $dados = $dashCtrl->index(); 
        
        $cards = $dados['cards'];
        $produtos = $dados['produtos'];
    }
    
    if ($page === 'transactions') {
        require_once __DIR__ . '/../app/Controllers/transactioncontroller.php';
        $transCtrl = new TransactionController();
        $dadosTrans = $transCtrl->index(); 
        
        $movimentacoes = $dadosTrans['movimentacoes'];
        $listaProdutos = $dadosTrans['produtos']; 
        $filtros = $dadosTrans['filtros'];
        $paginacao = $dadosTrans['paginacao'];
    }

    if ($page === 'inventory') {
        require_once __DIR__ . '/../app/Controllers/inventorycontroller.php';
        $invCtrl = new InventoryController();
        $dadosInv = $invCtrl->index();
        
        $listaInventario = $dadosInv['produtos'];
        $listaCategorias = $dadosInv['categorias'];
    }
    
    if ($page === 'reports') {
        require_once __DIR__ . '/../app/Controllers/reportcontroller.php';
        $repCtrl = new ReportController();
        $dadosRelatorio = $repCtrl->index();
    }

    if ($page === 'suppliers') {
        require_once __DIR__ . '/../app/Controllers/suppliercontroller.php';
        $supCtrl = new SupplierController();
        $dadosForn = $supCtrl->index();
        
        $fornecedores = $dadosForn['fornecedores'];
    }
    if ($page === 'settings') {
        require_once __DIR__ . '/../app/Controllers/settingscontroller.php';
        $setCtrl = new SettingsController();
        $dadosSettings = $setCtrl->index();
        
        $categorias = $dadosSettings['categorias'];
        $usuarios = $dadosSettings['usuarios'];
    }

    require_once __DIR__ . "/../app/Views/{$page}.php";
} else {
    header("Location: index.php?page={$default_page}");
    exit();
}
?>