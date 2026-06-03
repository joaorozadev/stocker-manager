document.addEventListener('DOMContentLoaded', () => {
    
    /* =========================================
       1. TEMA GLOBAL (DARK/LIGHT MODE)
       ========================================= */
    const currentTheme = localStorage.getItem('stocker_theme') || 'light';
    document.documentElement.setAttribute('data-theme', currentTheme);

    const svgSun = `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>`;
    const svgMoon = `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>`;

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('stocker_theme', theme);
    }

    const themeMenuBtn = document.getElementById('themeToggleMenu');
    
    if (themeMenuBtn) {
        const updateThemeBtnUI = (theme) => {
            if (theme === 'dark') {
                themeMenuBtn.innerHTML = '<i class="fa-solid fa-sun"></i> <span>Modo Claro</span>';
            } else {
                themeMenuBtn.innerHTML = '<i class="fa-solid fa-moon"></i> <span>Modo Escuro</span>';
            }
        };
        
        updateThemeBtnUI(currentTheme);

        themeMenuBtn.addEventListener('click', (e) => {
            e.preventDefault(); 
            e.stopPropagation(); 
            
            const newTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            applyTheme(newTheme);
            updateThemeBtnUI(newTheme);
        });
    }

    const themeBtn = document.getElementById('themeToggle');
    if (themeBtn) {
        themeBtn.style.cssText = 'color: var(--text-main); display: flex; align-items: center; justify-content: center;';
        themeBtn.innerHTML = currentTheme === 'dark' ? svgSun : svgMoon;
        
        themeBtn.addEventListener('click', () => {
            const newTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            applyTheme(newTheme);
            themeBtn.innerHTML = newTheme === 'dark' ? svgSun : svgMoon;
        });
    }

    /* =========================================
       2. FUNÇÕES UTILITÁRIAS GERAIS
       ========================================= */
    const openModal = (modal) => { if (modal) modal.classList.remove('hidden'); };
    const closeModal = (modal) => { if (modal) modal.classList.add('hidden'); };

    const isValidEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

    const clearError = (input) => {
        if (!input) return;
        input.classList.remove('input-error');
        const parent = input.closest('.form-group') || input.parentElement;
        const existingError = parent.querySelector('.error-message');
        if (existingError) existingError.remove();
    };

    const showError = (input, message) => {
        if (!input) return;
        clearError(input);
        input.classList.add('input-error');
        const errorSpan = document.createElement('span');
        errorSpan.className = 'error-message';
        errorSpan.innerText = message;
        
        const parent = input.closest('.password-wrapper') ? input.parentElement.parentElement : input.parentElement;
        parent.appendChild(errorSpan);
    };

    const filterElements = (inputElement, itemSelector, checkFunction) => {
        if (!inputElement) return;
        inputElement.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            document.querySelectorAll(itemSelector).forEach(item => {
                item.style.display = checkFunction(item, term) ? '' : 'none';
            });
        });
    };

    /* =========================================
       3. INTERAÇÕES DE UI (SIDEBAR & SENHA)
       ========================================= */
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebar && sidebarToggle) {
        if (localStorage.getItem('sidebar_collapsed') === 'true') sidebar.classList.add('collapsed');
        
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebar_collapsed', sidebar.classList.contains('collapsed'));
    
        if (window.innerWidth <= 800 && sidebar && !sidebar.classList.contains('collapsed')) {
            sidebar.classList.add('collapsed');
        }
        });
    }

    const togglePasswordBtn = document.getElementById('togglePassword');
    const loginPasswordInput = document.getElementById('password');
    if (togglePasswordBtn && loginPasswordInput) {
        togglePasswordBtn.addEventListener('click', function () {
            const isPassword = loginPasswordInput.type === 'password';
            loginPasswordInput.type = isPassword ? 'text' : 'password';
            this.innerHTML = isPassword 
                ? `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>`
                : `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;
        });
    }

    const btnDemo = document.getElementById('btnDemo');
    if (btnDemo) {
        btnDemo.addEventListener('click', () => {
            const form = document.getElementById('loginForm');
            if(form && form.email && form.password) {
                form.email.value = 'demo@stockermanager.com';
                form.password.value = 'demo123';
            }
        });
    }

    /* =========================================
       4. SISTEMA DE PESQUISA E FILTROS
       ========================================= */
    filterElements(document.getElementById('searchInput'), '#tableBody tr', (row, term) => row.textContent.toLowerCase().includes(term));
    filterElements(document.getElementById('transactionSearch'), '#transactionTableBody tr', (row, term) => row.textContent.toLowerCase().includes(term));

    /* =========================================
       5. CONTROLE CENTRALIZADO DE MODAIS
       ========================================= */
    function setupModal(modalId, openBtnId, closeBtnIds, formId) {
        const modal = document.getElementById(modalId);
        const openBtn = document.getElementById(openBtnId);
        const form = document.getElementById(formId);

        if (!modal) return;

        if (openBtn) {
            const newOpenBtn = openBtn.cloneNode(true);
            openBtn.parentNode.replaceChild(newOpenBtn, openBtn);
            newOpenBtn.addEventListener('click', () => openModal(modal));
        }

        closeBtnIds.forEach(id => {
            const btn = document.getElementById(id);
            if (btn) btn.addEventListener('click', () => { closeModal(modal); if(form) form.reset(); });
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) { closeModal(modal); if(form) form.reset(); }
        });
    }

    setupModal('modalNovoProduto', 'btnNovoProduto', ['btnCloseModal', 'btnCancelModal'], 'formNovoProduto');
    setupModal('modalProduto', 'btnNovoProduto', ['btnCloseModal', 'btnCancelarModal'], 'formNovoProduto');
    setupModal('modalNovaMovimentacao', 'btnNovaMovimentacao', ['btnCloseTransModal', 'btnCancelTransModal'], 'formNovaMovimentacao');

    /* =========================================
       6. VALIDAÇÃO DE AUTENTICAÇÃO
       ========================================= */
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            const email = document.getElementById('email');
            const pass = document.getElementById('password');
            let isValid = true;

            [email, pass].forEach(clearError);

            if (!email.value.trim() || !isValidEmail(email.value)) { showError(email, 'E-mail inválido.'); isValid = false; }
            if (!pass.value) { showError(pass, 'A senha é obrigatória.'); isValid = false; }

            if (!isValid) {
                e.preventDefault(); 
            } else {
                const btn = document.getElementById('btnSubmit');
                if (btn) {
                    const btnText = btn.querySelector('.btn-text');
                    const spinner = btn.querySelector('.spinner');
                    
                    if (btnText) btnText.textContent = 'Autenticando...';
                    if (spinner) spinner.classList.remove('hidden');
                    
                    setTimeout(() => { btn.disabled = true; }, 50);
                }
            }
        });
    }

    /* =========================================
       7. DROPDOWNS (KEBAB MENU & PERFIL)
       ========================================= */
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.toggle-dropdown');
        
        if (btn) {
            const sidebar = document.getElementById('sidebar');
            if (btn.classList.contains('profile-dropdown-btn') && sidebar && sidebar.classList.contains('collapsed')) {
                return; 
            }

            const menu = btn.nextElementSibling;
            
            document.querySelectorAll('.dropdown-menu').forEach(d => {
                if (d !== menu) d.classList.add('hidden');
            });
            
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                
                if (!menu.classList.contains('profile-menu')) {
                    const rect = btn.getBoundingClientRect();
                    let topPos = rect.bottom + 8;
                    let leftPos = rect.right - menu.offsetWidth; 
                    
                    if (leftPos < 10) leftPos = rect.left;
                    if (topPos + menu.offsetHeight > window.innerHeight - 10) {
                        topPos = rect.top - menu.offsetHeight - 8;
                    }
                    
                    menu.style.position = 'fixed';
                    menu.style.right = 'auto';
                    menu.style.transform = 'none';
                    menu.style.top = topPos + 'px';
                    menu.style.left = leftPos + 'px';
                } else {
                    menu.style.position = '';
                    menu.style.top = '';
                    menu.style.left = '';
                    menu.style.right = '';
                    menu.style.transform = '';
                }
            } else {
                menu.classList.add('hidden');
            }
        } else {
            document.querySelectorAll('.dropdown-menu').forEach(d => d.classList.add('hidden'));
        }
    });


    /* =========================================
       8. EDIÇÃO DE PRODUTOS
       ========================================= */
    document.addEventListener('click', function(e) {
        const btnEdit = e.target.closest('.btn-editar-produto');
        if (btnEdit) {
            e.preventDefault(); 
            
            if (document.getElementById('editProdId')) document.getElementById('editProdId').value = btnEdit.dataset.id;
            if (document.getElementById('editProdNome')) document.getElementById('editProdNome').value = btnEdit.dataset.nome;
            if (document.getElementById('editProdCategoria')) document.getElementById('editProdCategoria').value = btnEdit.dataset.categoria;
            if (document.getElementById('editProdPreco')) document.getElementById('editProdPreco').value = btnEdit.dataset.preco;

            document.querySelectorAll('.dropdown-menu').forEach(d => d.classList.add('hidden'));
            
            const modalEdit = document.getElementById('modalEditarProduto');
            if (modalEdit) modalEdit.classList.remove('hidden');
        }
    });

    const btnCloseEdit = document.getElementById('btnCloseEditModal');
    const btnCancelEdit = document.getElementById('btnCancelEditModal');
    const modalEdit = document.getElementById('modalEditarProduto');
    
    if (btnCloseEdit) btnCloseEdit.addEventListener('click', () => modalEdit.classList.add('hidden'));
    if (btnCancelEdit) btnCancelEdit.addEventListener('click', () => modalEdit.classList.add('hidden'));

    /* =========================================
       9. REPOR ESTOQUE
       ========================================= */
    document.addEventListener('click', function(e) {
        const btnRepor = e.target.closest('.btn-repor-estoque');
        if (btnRepor) {
            e.preventDefault(); 
            
            if (document.getElementById('reporProdId')) document.getElementById('reporProdId').value = btnRepor.dataset.id;
            if (document.getElementById('reporProdNome')) document.getElementById('reporProdNome').value = btnRepor.dataset.nome;

            document.querySelectorAll('.dropdown-menu').forEach(d => d.classList.add('hidden'));
            
            const modalRepor = document.getElementById('modalReporEstoque');
            if (modalRepor) modalRepor.classList.remove('hidden');
        }
    });

    const btnCloseRepor = document.getElementById('btnCloseReporModal');
    const btnCancelRepor = document.getElementById('btnCancelReporModal');
    const modalRepor = document.getElementById('modalReporEstoque');
    
    if (btnCloseRepor) btnCloseRepor.addEventListener('click', () => modalRepor.classList.add('hidden'));
    if (btnCancelRepor) btnCancelRepor.addEventListener('click', () => modalRepor.classList.add('hidden'));

    /* =========================================
       10. FILTROS DO INVENTÁRIO (FRONT-END)
       ========================================= */
    const inventoryContainer = document.querySelector('.inventory-grid-wrapper');
    
    if (inventoryContainer) {
        const searchInput = document.getElementById('searchInput') || document.getElementById('inventorySearch');
        const catBtns = document.querySelectorAll('.cat-btn');
        const priceRange = document.getElementById('priceRange');
        const statusTags = document.querySelectorAll('.filter-tags-container .tag');
        const sortSelect = document.getElementById('sortSelect') || document.querySelector('.sort-select');
        const countDisplay = document.querySelector('.grid-controls strong');

        function aplicarFiltros() {
            const productCards = document.querySelectorAll('.product-card');
            
            const termoBusca = searchInput ? searchInput.value.toLowerCase() : '';
            const precoMaximo = priceRange ? parseFloat(priceRange.value) : 10000;
            
            const btnCatAtivo = document.querySelector('.cat-btn.active');
            const categoriaAtiva = btnCatAtivo ? btnCatAtivo.textContent.split('(')[0].trim() : 'Todos';
            
            const tagStatusAtiva = document.querySelector('.filter-tags-container .tag.active');
            const statusAtivo = tagStatusAtiva ? tagStatusAtiva.getAttribute('data-status') : 'todos';

            let visiveis = 0;

            productCards.forEach(card => {
                const nome = card.dataset.nome;
                const categoria = card.dataset.categoria;
                const preco = parseFloat(card.dataset.preco);
                const statusClass = card.dataset.status;

                const matchBusca = nome.includes(termoBusca) || categoria.toLowerCase().includes(termoBusca);
                const matchCategoria = (categoriaAtiva === 'Todos') || (categoria === categoriaAtiva);
                const matchPreco = preco <= precoMaximo || precoMaximo >= 10000;

                let matchStatus = true;
                if (statusAtivo === 'stock') {
                    matchStatus = (statusClass === 'stock' || statusClass === 'warning');
                } else if (statusAtivo === 'out') {
                    matchStatus = (statusClass === 'out');
                }

                if (matchBusca && matchCategoria && matchPreco && matchStatus) {
                    card.style.display = ''; 
                    visiveis++;
                } else {
                    card.style.display = 'none'; 
                }
            });

            if (countDisplay) countDisplay.textContent = visiveis;
        }

        if (searchInput) searchInput.addEventListener('input', aplicarFiltros);
        
        if (priceRange) {
            const priceLabel = priceRange.nextElementSibling.children[1];
            priceRange.addEventListener('input', function() {
                priceLabel.textContent = this.value >= 10000 ? 'R$ 10.000+' : `R$ ${this.value}`;
                aplicarFiltros();
            });
        }

        catBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                catBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                aplicarFiltros();
            });
        });

        statusTags.forEach(tag => {
            tag.addEventListener('click', function() {
                statusTags.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                aplicarFiltros();
            });
        });

        const btnLimpar = document.getElementById('btnLimparFiltros') || document.querySelector('.btn-secondary.w-full');
        if (btnLimpar) {
            btnLimpar.addEventListener('click', () => {
                if(searchInput) searchInput.value = '';
                
                catBtns.forEach(b => b.classList.remove('active'));
                if(catBtns[0]) catBtns[0].classList.add('active'); 
                
                statusTags.forEach(t => t.classList.remove('active'));
                if(statusTags[0]) statusTags[0].classList.add('active'); 
                
                if(priceRange) {
                    priceRange.value = 10000;
                    priceRange.dispatchEvent(new Event('input')); 
                }
                
                if(sortSelect) sortSelect.selectedIndex = 0;
                
                aplicarFiltros();
                ordenarProdutos();
            });
        }

        function ordenarProdutos() {
            if(!sortSelect) return;
            const container = document.getElementById('productGrid');
            const cards = Array.from(container.querySelectorAll('.product-card'));
            const tipo = sortSelect.value;

            cards.sort((a, b) => {
                const precoA = parseFloat(a.dataset.preco);
                const precoB = parseFloat(b.dataset.preco);
                const qtdA = parseInt(a.dataset.quantidade) || 0; 
                const qtdB = parseInt(b.dataset.quantidade) || 0; 
                const idA = parseInt(a.dataset.id);
                const idB = parseInt(b.dataset.id);

                if (tipo === 'preco_asc') return precoA - precoB;
                if (tipo === 'preco_desc') return precoB - precoA;
                if (tipo === 'estoque_asc') return qtdA - qtdB;
                if (tipo === 'estoque_desc') return qtdB - qtdA;
                
                return idB - idA; 
            });

            cards.forEach(card => container.appendChild(card));
        } 

        if (sortSelect) sortSelect.addEventListener('change', ordenarProdutos);
        ordenarProdutos(); 
    }

    /* =========================================
       12. GRÁFICOS DE RELATÓRIO (CHART.JS)
       ========================================= */
    const chartsGrid = document.querySelector('.charts-grid');
    
    if (chartsGrid) {
        const flowCanvas = document.getElementById('flowChart');
        const categoryCanvas = document.getElementById('categoryChart');
        
        if (flowCanvas && categoryCanvas && typeof Chart !== 'undefined') {
            try {
                const rawLinha = chartsGrid.dataset.linha || '{"entradas":[], "saidas":[], "labels":[]}';
                const rawRosca = chartsGrid.dataset.rosca || '{"labels":[], "valores":[]}';
                
                const chartDataLinha = JSON.parse(rawLinha);
                const chartDataRosca = JSON.parse(rawRosca);

                new Chart(flowCanvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: chartDataLinha.labels || ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                        datasets: [
                            {
                                label: 'Entradas',
                                data: chartDataLinha.entradas || [],
                                borderColor: '#3b82f6', 
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                borderWidth: 2,
                                tension: 0.4, 
                                fill: true
                            },
                            {
                                label: 'Saídas',
                                data: chartDataLinha.saidas || [],
                                borderColor: '#ef4444', 
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'top', labels: { color: '#94a3b8' } } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#334155' }, ticks: { color: '#94a3b8' } },
                            x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
                        }
                    }
                });

                new Chart(categoryCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: (chartDataRosca.labels && chartDataRosca.labels.length > 0) ? chartDataRosca.labels : ['Sem Dados'],
                        datasets: [{
                            data: (chartDataRosca.valores && chartDataRosca.valores.length > 0) ? chartDataRosca.valores : [1],
                            backgroundColor: ['#3b82f6', '#8b5cf6', '#10b981', '#f59e0b', '#ec4899', '#ef4444', '#14b8a6'],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%', 
                        plugins: { legend: { position: 'bottom', labels: { color: '#94a3b8', padding: 20 } } }
                    }
                });
            } catch (erro) {
                console.error("Erro ao montar os gráficos:", erro);
            }
        }
    }

    /* =========================================
       13. FILTROS DO RELATÓRIO
       ========================================= */
    const filtroPeriodo = document.getElementById('filtroPeriodo');
    const filtroMes = document.getElementById('filtroMes');
    const filtroAnoGlobal = document.getElementById('filtroAnoGlobal');

    if (filtroPeriodo && filtroMes) {
        function aplicarFiltroFluxo() {
            const periodo = filtroPeriodo.value;
            const mes = filtroMes.value;
            const ano = filtroAnoGlobal ? filtroAnoGlobal.value : 'geral'; 
            window.location.href = `index.php?page=reports&periodo=${periodo}&mes=${mes}&ano=${ano}`;
        }

        filtroPeriodo.addEventListener('change', aplicarFiltroFluxo);
        filtroMes.addEventListener('change', aplicarFiltroFluxo);
        
        if (filtroAnoGlobal) {
            filtroAnoGlobal.addEventListener('change', aplicarFiltroFluxo);
        }
    }

    /* =========================================
       14. PAGINAÇÃO E FILTROS (TRANSAÇÕES)
       ========================================= */
    const formFiltros = document.getElementById('filterTransactionsForm');
    
    if (formFiltros) {
        const inputPagina = document.getElementById('inputPaginaAtual');
        const selectLimite = document.getElementById('limitePagina');
        const inputBusca = document.getElementById('transactionSearch');
        const inputsDiretos = formFiltros.querySelectorAll('select[name="tipo"], input[type="date"]');

        window.mudarPagina = function(novaPagina) {
            if (inputPagina) {
                inputPagina.value = novaPagina;
                formFiltros.submit();
            }
        };

        if (selectLimite) {
            selectLimite.addEventListener('change', () => {
                inputPagina.value = 1; 
                formFiltros.submit();
            });
        }

        inputsDiretos.forEach(input => {
            input.addEventListener('change', () => {
                inputPagina.value = 1;
                formFiltros.submit();
            });
        });

        if (inputBusca) {
            let timeoutBusca;
            inputBusca.addEventListener('input', () => {
                clearTimeout(timeoutBusca);
                timeoutBusca = setTimeout(() => {
                    inputPagina.value = 1;
                    formFiltros.submit();
                }, 500); 
            });
            
            const tamanhoTexto = inputBusca.value.length;
            if (tamanhoTexto > 0) {
                inputBusca.focus();
                inputBusca.setSelectionRange(tamanhoTexto, tamanhoTexto);
            }
        }
    }

    /* =========================================
       15. FORNECEDORES
       ========================================= */
    const modalFornecedor = document.getElementById('modalNovoFornecedor');
    const btnNovoFornecedor = document.getElementById('btnNovoFornecedor') || document.querySelector('.topbar-actions .btn-primary');
    const btnCloseForn = document.getElementById('btnCloseFornecedorModal');
    const btnCancelForn = document.getElementById('btnCancelFornecedorModal');

    if (modalFornecedor && btnNovoFornecedor) {
        const toggleModalForn = () => modalFornecedor.classList.toggle('hidden');
        
        btnNovoFornecedor.addEventListener('click', toggleModalForn);
        if(btnCloseForn) btnCloseForn.addEventListener('click', toggleModalForn);
        if(btnCancelForn) btnCancelForn.addEventListener('click', toggleModalForn);
        
        modalFornecedor.addEventListener('click', (e) => {
            if (e.target === modalFornecedor) toggleModalForn();
        });
    }

    const formForn = document.getElementById('filterSuppliersForm');
    const inputBuscaForn = document.getElementById('supplierSearch');

    if (formForn && inputBuscaForn) {
        let timeoutForn;
        inputBuscaForn.addEventListener('input', () => {
            clearTimeout(timeoutForn);
            timeoutForn = setTimeout(() => {
                formForn.submit();
            }, 500);
        });

        const tamanhoTexto = inputBuscaForn.value.length;
        if (tamanhoTexto > 0) {
            inputBuscaForn.focus();
            inputBuscaForn.setSelectionRange(tamanhoTexto, tamanhoTexto);
        }
    }

    const modalEditarForn = document.getElementById('modalEditarFornecedor');
    const botoesEditarForn = document.querySelectorAll('.btn-edit-forn');

    if (modalEditarForn && botoesEditarForn.length > 0) {
        const toggleEditModal = () => modalEditarForn.classList.toggle('hidden');

        botoesEditarForn.forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('editFornId').value = btn.getAttribute('data-id');
                document.getElementById('editFornNome').value = btn.getAttribute('data-nome');
                document.getElementById('editFornCnpj').value = btn.getAttribute('data-cnpj');
                document.getElementById('editFornEmail').value = btn.getAttribute('data-email');
                document.getElementById('editFornTelefone').value = btn.getAttribute('data-tel');
                toggleEditModal();
            });
        });

        document.getElementById('btnCloseEditForn').addEventListener('click', toggleEditModal);
        document.getElementById('btnCancelEditForn').addEventListener('click', toggleEditModal);
    }

    const modalVerProd = document.getElementById('modalVerProdutos');
    const botoesVerProd = document.querySelectorAll('.btn-view-produtos');

    if (modalVerProd && botoesVerProd.length > 0) {
        const toggleViewModal = () => modalVerProd.classList.toggle('hidden');
        
        botoesVerProd.forEach(btn => {
            btn.addEventListener('click', () => {
                const fornId = btn.getAttribute('data-id');
                const fornNome = btn.getAttribute('data-nome');
                
                document.getElementById('tituloFornProdutos').textContent = fornNome;
                const tbody = document.getElementById('listaProdutosFornecedor');
                const loading = document.getElementById('loadingProdutos');
                
                tbody.innerHTML = '';
                loading.classList.remove('hidden');
                toggleViewModal();

                fetch(`index.php?action=api_produtos_fornecedor&id=${fornId}`)
                    .then(response => response.json())
                    .then(dados => {
                        loading.classList.add('hidden');
                        
                        if (dados.length === 0) {
                            tbody.innerHTML = '<tr><td colspan="3" style="text-align: center; color: #64748b; padding: 2rem;">Nenhum produto cadastrado para este fornecedor.</td></tr>';
                            return;
                        }

                        dados.forEach(prod => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td><strong>${prod.codigo_sku}</strong></td>
                                <td>${prod.nome}</td>
                                <td><span class="badge ${prod.quantidade_atual > 0 ? 'badge-success' : 'badge-danger'}">${prod.quantidade_atual} un.</span></td>
                            `;
                            tbody.appendChild(tr);
                        });
                    })
                    .catch(error => {
                        loading.classList.add('hidden');
                        tbody.innerHTML = '<tr><td colspan="3" style="text-align: center; color: red; padding: 2rem;">Erro ao carregar dados.</td></tr>';
                    });
            });
        });

        document.getElementById('btnCloseViewProd').addEventListener('click', toggleViewModal);
        document.getElementById('btnOkViewProd').addEventListener('click', toggleViewModal);
    }

    /* =========================================
       16. CONTROLE DE ABAS (TELA DE CONFIGURAÇÕES)
       ========================================= */
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    if (tabButtons.length > 0 && tabContents.length > 0) {
        function switchTab(tabId) {
            tabButtons.forEach(btn => btn.classList.toggle('active', btn.getAttribute('data-tab') === tabId));
            tabContents.forEach(content => content.classList.toggle('active', content.id === `tab-${tabId}`));
        }

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetTab = button.getAttribute('data-tab');
                switchTab(targetTab);
                
                const newUrl = window.location.pathname + '?page=settings&tab=' + targetTab;
                window.history.pushState({ path: newUrl }, '', newUrl);
            });
        });

        const urlParams = new URLSearchParams(window.location.search);
        const activeTabParam = urlParams.get('tab');
        if (activeTabParam) {
            switchTab(activeTabParam);
        }
    }
    /* =========================================
   17. SISTEMA DE ALERTAS 
   ========================================= */
    window.addEventListener('load', function() {
    const params = new URLSearchParams(window.location.search);
    
    if (params.get('erro') === 'estoque_insuficiente') {
        setTimeout(() => {
            alert("ATENÇÃO: Operação cancelada.\n\nVocê tentou retirar uma quantidade maior do que a disponível em estoque. O estoque não pode ficar negativo.");
        }, 100); 

        params.delete('erro');
        const novaUrl = window.location.pathname + '?' + params.toString();
        window.history.replaceState({}, document.title, novaUrl);
    }
    });
});