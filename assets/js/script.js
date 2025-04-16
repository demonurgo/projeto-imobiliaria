/**
 * Custom scripts for NFSe-DIMOB System
 */

// Document Ready Function
$(document).ready(function() {
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    });
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert-dismissible').fadeOut('slow');
    }, 5000);
    
    // Confirm deletion
    $('.btn-delete').on('click', function(e) {
        if (!confirm('Tem certeza que deseja excluir este item?')) {
            e.preventDefault();
        }
    });
    
    // File input change - show filename
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });
    
    // DIMOB form validation
    $('#dimob-form').on('submit', function(e) {
        var year = $('#ano_referencia').val();
        
        if (!year || year.length != 4) {
            alert('Por favor, informe um ano de referência válido com 4 dígitos.');
            e.preventDefault();
            return false;
        }
    });
    
    // Mask for Brazilian CPF/CNPJ
    if ($.fn.mask) {
        $('.cpf-mask').mask('000.000.000-00');
        $('.cnpj-mask').mask('00.000.000/0000-00');
        $('.phone-mask').mask('(00) 00000-0000');
        $('.cep-mask').mask('00000-000');
        $('.money').mask('#.##0,00', {reverse: true});
    }
    
    // Toggle sidebar on mobile
    $('#sidebarToggle').on('click', function() {
        $('body').toggleClass('sidebar-toggled');
        $('.sidebar').toggleClass('toggled');
    });
    
    // Close any open menu when window is resized
    $(window).resize(function() {
        if ($(window).width() < 768) {
            $('.sidebar').addClass('toggled');
        }
    });
    
    //--------------------------------------------------------------------------
    // DIMOB MODULE - Functions for DIMOB module forms
    //--------------------------------------------------------------------------
    
    // Adicionar indicador de carregamento aos formulários
    $(document).on('submit', 'form', function() {
        const submitBtn = $(this).find('button[type="submit"]');
        if (submitBtn.length) {
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i> Processando...');
            submitBtn.prop('disabled', true);
            // Restaurar o botão se o formulário não for enviado por algum motivo
            setTimeout(function() {
                if (submitBtn.prop('disabled')) {
                    submitBtn.html(originalText);
                    submitBtn.prop('disabled', false);
                }
            }, 5000);
        }
    });
    
    // Inicializar DataTable para tabelas de listagem
    // Removemos a inicialização automática para tabelas DIMOB aqui, pois a página dimob/listar.php 
    // tem sua própria inicialização personalizada. Isso evita conflitos entre múltiplas inicializações.
    if ($('#table-notas').length > 0 && !$.fn.DataTable.isDataTable('#table-notas') && !$('body').hasClass('dimob-page')) {
        $('#table-notas').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
            },
            "responsive": true,
            "order": [[ 2, "desc" ]], // Ordenar por competência (desc)
            "pageLength": 25,
            "dom": '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-12"tr>><"row"<"col-sm-5"i><"col-sm-7"p>>'
        });
    }
    
    // Função para atualizar os campos do tomador
    $(document).on('change', '#tomador_id', function() {
        var selectElement = $(this);
        var selectedOption = $('option:selected', selectElement);
        
        // Limpar campos antigos
        $('#tomador_nome').val('');
        $('#tomador_cpf_cnpj').val('');
        
        if (selectedOption.val()) {
            // Usar data-attributes diretamente para evitar parsing
            var cpfCnpj = selectedOption.data('cpf-cnpj') || '';
            var nome = selectedOption.data('nome') || '';
            
            // Preencher os campos
            $('#tomador_cpf_cnpj').val(cpfCnpj);
            $('#tomador_nome').val(nome);
            
            console.log("Tomador selecionado: ID=", selectedOption.val(), 
                     "Nome=", nome, 
                     "CPF/CNPJ=", cpfCnpj);
        }
    });

    // Função para atualizar os campos do inquilino
    $(document).on('change', '#inquilino_id', function() {
        var selectElement = $(this);
        var selectedOption = $('option:selected', selectElement);
        
        // Limpar campos antigos
        $('#inquilino_nome').val('');
        $('#inquilino_cpf_cnpj').val('');
        $('#inquilino_email').val('');
        
        if (selectedOption.val()) {
            // Usar data-attributes diretamente para evitar parsing
            var cpfCnpj = selectedOption.data('cpf-cnpj') || '';
            var nome = selectedOption.data('nome') || '';
            var email = selectedOption.data('email') || '';
            
            // Preencher os campos
            $('#inquilino_cpf_cnpj').val(cpfCnpj);
            $('#inquilino_nome').val(nome);
            $('#inquilino_email').val(email);
            
            console.log("Inquilino selecionado: ID=", selectedOption.val(), 
                     "Nome=", nome, 
                     "CPF/CNPJ=", cpfCnpj);
        }
    });
    
    // Função para configurar o valor do aluguel a partir do imóvel (para editar_nota.php)
    if ($('#imovel_id').length > 0 && $('.dimob-edit-form').length > 0) {
        function configurarDadosImovel() {
            var imovelId = $('#imovel_id').val();
            if (!imovelId) return;
            
            // Obtemos os dados do imóvel diretamente da variável PHP que recuperamos
            // nos atributos data do imóvel atual
            var valorAluguel = $('#imovel_id').data('valor-aluguel') || '0.00';
            var tipo = $('#imovel_id').data('tipo') || 'urbano';
            
            // Formatar o valor do aluguel
            if (valorAluguel) {
                var valorFormatado = parseFloat(valorAluguel).toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).replace('.', ',');
                
                $('#valor_aluguel').val(valorFormatado);
            }
            
            // Definir o tipo do imóvel
            if (tipo) {
                $('#imovel_tipo').val(tipo);
            }
            
            console.log("Dados do imóvel configurados. Valor:", valorAluguel, "Tipo:", tipo);
        }
        
        // Executar a função para configurar os dados do imóvel
        configurarDadosImovel();
    }
    
    // Função para formatar os campos de CPF/CNPJ
    function formatarCPFCNPJ() {
        $('#tomador_cpf_cnpj, #inquilino_cpf_cnpj').each(function() {
            var valor = $(this).val().replace(/\D/g, '');
            if (valor.length > 0) {
                if (valor.length > 11) {
                    // CNPJ
                    $(this).unmask(); // Remove máscara anterior, se houver
                    $(this).mask('00.000.000/0000-00');
                } else {
                    // CPF
                    $(this).unmask(); // Remove máscara anterior, se houver
                    $(this).mask('000.000.000-00');
                }
            }
        });
    }
    
    // Inicializador para o formulário de edição de notas para DIMOB
    if ($('.dimob-edit-form').length > 0) {
        // Confirmação antes de sair da página se houver mudanças
        let formChanged = false;
        
        // Monitorar mudanças em todos os campos do formulário
        $('.dimob-edit-form input, .dimob-edit-form select, .dimob-edit-form textarea').on('change', function() {
            formChanged = true;
            console.log('Formulário modificado pelo usuário');
        });
        
        // Monitorar mudanças específicas nos campos monetários
        $('.dimob-edit-form .money').on('input', function() {
            var currentVal = $(this).val().replace(/\D/g, '');
            var originalVal = String($(this).data('valor-original')).replace(/\D/g, '');
            
            // Se o valor for diferente do original (ignorando formatação)
            if (currentVal !== originalVal) {
                formChanged = true;
                console.log('Valor monetário modificado:', $(this).attr('id'));
            }
        });
        
        // Formatador de CPF/CNPJ - Trigger inicial
        formatarCPFCNPJ();
        
        // Resetar flag ao submeter o formulário
        $('.dimob-edit-form').on('submit', function() {
            formChanged = false;
        });
        
        // Avisar ao sair da página se houver mudanças
        $(window).on('beforeunload', function() {
            if (formChanged) {
                return "Existem alterações não salvas. Deseja realmente sair da página?";
            }
        });
        
        // Adicionar eventos de submit ao formulário para garantir dados consistentes
        $('.dimob-edit-form').on('submit', function(e) {
            // Impedir o envio default do formulário
            e.preventDefault();
            
            console.log('Formulário sendo enviado, processando valores...');
            
            // Obter os valores originais dos campos monetários (sem máscara)
            var valorAluguelOriginal = $('#valor_aluguel').data('valor-original') || $('#valor_aluguel').val();
            var valorServicosOriginal = $('#valor_servicos').data('valor-original') || $('#valor_servicos').val();
            
            console.log('Valor aluguel original:', valorAluguelOriginal);
            console.log('Valor serviços original:', valorServicosOriginal);
            
            // Se não foram alterados pelo usuário, usar os valores originais
            if (!formChanged) {
                $('#valor_aluguel').val(valorAluguelOriginal);
                $('#valor_servicos').val(valorServicosOriginal);
                console.log('Usando valores originais pois não houve alteração');
            } else {
                // Se foram alterados, remover a máscara e formatar corretamente
                $('.money').each(function() {
                    var valor = $(this).val();
                    // Se o valor contiver vírgula, é formato brasileiro
                    if (valor && valor.includes(',')) {
                        // Remover pontos e substituir vírgula por ponto
                        valor = valor.replace(/\./g, '').replace(',', '.');
                        $(this).val(valor);
                        console.log('Valor formatado:', $(this).attr('id'), valor);
                    }
                });
            }
            
            // Garantir que os campos de data estão no formato correto
            var dataEmissao = $('#data_emissao').val();
            if (dataEmissao && !dataEmissao.includes('T')) {
                $('#data_emissao').val(dataEmissao + 'T00:00:00');
            }
            
            // Enviar o formulário
            this.submit();
        });
    }
});

