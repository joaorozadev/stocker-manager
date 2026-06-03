<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="index.php?page=dashboard" class="logo" style="text-decoration: none;">
            <i class="fa-solid fa-cubes" style="color: var(--primary); font-size: 1.5rem;"></i>
            <h2 class="logo-text">Stocker<span>Mgr</span></h2>
        </a>
        <i class="fa-solid fa-bars toggle-btn" id="sidebarToggle"></i>
    </div>

    <nav class="sidebar-nav">
        <a href="index.php?page=dashboard" class="<?= ($page === 'dashboard') ? 'active' : '' ?>" title="Dashboard">
            <i class="fa-solid fa-border-all"></i> <span class="nav-text">Dashboard</span>
        </a>
        
        <a href="index.php?page=inventory" class="<?= ($page === 'inventory') ? 'active' : '' ?>" title="Inventário">
            <i class="fa-solid fa-box-open"></i> <span class="nav-text">Inventário</span>
        </a>
        
        <a href="index.php?page=transactions" class="<?= ($page === 'transactions') ? 'active' : '' ?>" title="Entradas/Saídas">
            <i class="fa-solid fa-truck-ramp-box"></i> <span class="nav-text">Entradas/Saídas</span>
        </a>
        
        <a href="index.php?page=reports" class="<?= ($page === 'reports') ? 'active' : '' ?>" title="Relatórios">
            <i class="fa-solid fa-chart-pie"></i> <span class="nav-text">Relatórios</span>
        </a>
        <a href="index.php?page=suppliers" class="<?= ($page === 'suppliers') ? 'active' : '' ?>" title="Fornecedores">
            <i class="fa-solid fa-users"></i> <span class="nav-text">Fornecedores</span>
        </a>
    </nav>

    <?php 
        $nomeSessao = $_SESSION['user_name'] ?? 'Usuário';
        $cargoSessao = $_SESSION['user_role'] ?? 'Colaborador';
        
        $partesNome = explode(' ', trim($nomeSessao));
        $iniciais = strtoupper(substr($partesNome[0], 0, 1));
        if (count($partesNome) > 1) {
            $iniciais .= strtoupper(substr($partesNome[1], 0, 1));
        }
    ?>
    <div class="sidebar-user-block relative">
        <button class="profile-dropdown-btn toggle-dropdown">
            <div class="user-avatar">
                <?= $iniciais ?>
            </div>
            <div class="user-details">
                <span class="user-name"><?= htmlspecialchars($nomeSessao) ?></span>
                <span class="user-role"><?= htmlspecialchars($cargoSessao) ?></span>
            </div>
            <i class="fa-solid fa-chevron-up user-chevron"></i>
        </button>

        <div class="dropdown-menu profile-menu hidden">
            <a href="index.php?page=settings" class="dropdown-item">
                <i class="fa-solid fa-gear"></i> <span>Configurações</span>
            </a>
            
            <button id="themeToggleMenu" class="dropdown-item w-full">
                <i class="fa-solid fa-moon"></i> <span>Modo Escuro</span>
            </button>
            
            <div class="dropdown-divider"></div>
            
            <a href="index.php?action=logout" class="dropdown-item text-danger">
                <i class="fa-solid fa-right-from-bracket"></i> <span>Sair do Sistema</span>
            </a>
        </div>
    </div>
</aside>