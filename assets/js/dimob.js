/**
 * Scripts específicos para o módulo DIMOB
 * Versão atualizada com melhorias de UX
 */

var portugueseLanguage = {
	"sEmptyTable": "Nenhum registro encontrado",
	"sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
	"sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
	"sInfoFiltered": "(Filtrados de _MAX_ registros)",
	"sInfoPostFix": "",
	"sInfoThousands": ".",
	"sLengthMenu": "Mostrar _MENU_ resultados por página",
	"sLoadingRecords": "Carregando...",
	"sProcessing": "Processando...",
	"sZeroRecords": "Nenhum registro encontrado",
	"sSearch": "Filtrar: ",
	"oPaginate": {
		"sNext": "Próximo",
		"sPrevious": "Anterior",
		"sFirst": "Primeiro",
		"sLast": "Último"
	},
	"oAria": {
		"sSortAscending": ": Ordenar colunas de forma ascendente",
		"sSortDescending": ": Ordenar colunas de forma descendente"
	},
	"select": {
		"rows": {
			"_": "Selecionado %d linhas",
			"0": "Nenhuma linha selecionada",
			"1": "Selecionado 1 linha"
		}
	}
};

$(document).ready(function() {
    // Adicionar indicador de carregamento
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
    // Inicializar o DataTable para tabelas de listagem - com verificação
    if ($('#table-notas').length > 0 && !$.fn.DataTable.isDataTable('#table-notas')) {
        $('#table-notas').DataTable({
            "language": {
                ...portugueseLanguage, // Spread operator para adicionar todas as traduções
				"searchPlaceholder": "Filtrar"
            },
            "responsive": true,
            "order": [[ 2, "desc" ]], // Ordenar por competência (desc)
            "pageLength": 25,
            "dom": '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-12"tr>><"row"<"col-sm-5"i><"col-sm-7"p>>',
            "drawCallback": function(settings) {
                // Adicionar classes para melhorar o visual
                $('#table-notas').addClass('border-0');
                $('#table-notas thead th').addClass('bg-light');
            }
        });
    }
    
    // Inicializar o Select2 para os selects com melhorias visuais
    if ($('.select2').length > 0) {
        $('.select2').each(function() {
            // Verificar se já foi inicializado
            if ($(this).hasClass('select2-hidden-accessible')) return;
            
            $(this).select2({
                theme: 'bootstrap-5',
                width: '100%', // Assegura largura correta
                placeholder: 'Selecione uma opção',
                allowClear: true,
                dropdownParent: $('body'),
                templateResult: formatSelectOption
            });
        });
    }
    
    // Função para formatar as opções no dropdown do select2
    function formatSelectOption(option) {
        if (!option.id) return option.text;
        
        // Adicionar ícones às opções com base no texto ou atributos
        let icon = '';
        const text = $(option.element).text();
        
        if ($(option.element).closest('select').attr('id') === 'prestador_id') {
            icon = '<i class="fas fa-building mr-2 text-primary"></i>';
        } else if ($(option.element).closest('select').attr('id') === 'tomador_id') {
            icon = '<i class="fas fa-user mr-2 text-info"></i>';
        } else if ($(option.element).closest('select').attr('id') === 'imovel_id') {
            icon = '<i class="fas fa-home mr-2 text-success"></i>';
        } else if ($(option.element).closest('select').attr('id') === 'inquilino_id') {
            icon = '<i class="fas fa-user-friends mr-2 text-warning"></i>';
        } else if ($(option.element).closest('select').attr('id') === 'ano') {
            icon = '<i class="fas fa-calendar-alt mr-2 text-secondary"></i>';
        }
        
        return $('<span>' + icon + text + '</span>');
    }
    
    // Inicializar máscara para campos monetários
    if ($('.money').length > 0) {
        $('.money').each(function() {
            if (!$(this).data('masked')) {
                $(this).mask('#.##0,00', {reverse: true});
                $(this).data('masked', true);
            }
        });
    }
    
    // Função para popular os dados do tomador
    $('#tomador_id').off('change').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var cpfCnpj = selectedOption.attr('data-cpf-cnpj') || '';
        var endereco = selectedOption.attr('data-endereco') || '';
        var numero = selectedOption.attr('data-numero') || '';
        var complemento = selectedOption.attr('data-complemento') || '';
        var bairro = selectedOption.attr('data-bairro') || '';
        var cidade = selectedOption.attr('data-cidade') || '';
        var uf = selectedOption.attr('data-uf') || '';
        
        $('#tomador_cpf_cnpj').val(cpfCnpj);
        
        var enderecoCompleto = endereco;
        if (numero) enderecoCompleto += ', ' + numero;
        if (complemento) enderecoCompleto += ' - ' + complemento;
        if (bairro) enderecoCompleto += ' - ' + bairro;
        if (cidade) enderecoCompleto += ' - ' + cidade + '/' + uf;
        
        $('#tomador_endereco').val(enderecoCompleto);
        
        console.log("Tomador alterado. CPF/CNPJ:", cpfCnpj, "Endereço:", enderecoCompleto);
    });
    
    // Função para popular os dados do inquilino
    $('#inquilino_id').off('change').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var cpfCnpj = selectedOption.attr('data-cpf-cnpj') || '';
        var telefone = selectedOption.attr('data-telefone') || '';
        var email = selectedOption.attr('data-email') || '';
        
        $('#inquilino_cpf_cnpj').val(cpfCnpj);
        $('#inquilino_telefone').val(telefone);
        $('#inquilino_email').val(email);
        
        console.log("Inquilino alterado. CPF/CNPJ:", cpfCnpj);
    });
    
    // Função para popular os dados do imóvel
    $('#imovel_id').off('change').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var endereco = selectedOption.attr('data-endereco') || '';
        var numero = selectedOption.attr('data-numero') || '';
        var complemento = selectedOption.attr('data-complemento') || '';
        var bairro = selectedOption.attr('data-bairro') || '';
        var cidade = selectedOption.attr('data-cidade') || '';
        var uf = selectedOption.attr('data-uf') || '';
        var valorAluguel = selectedOption.attr('data-valor-aluguel') || '0.00';
        var tipo = selectedOption.attr('data-tipo') || 'urbano';
        
        var enderecoCompleto = endereco;
        if (numero) enderecoCompleto += ', ' + numero;
        if (complemento) enderecoCompleto += ' - ' + complemento;
        if (bairro) enderecoCompleto += ' - ' + bairro;
        if (cidade) enderecoCompleto += ' - ' + cidade + '/' + uf;
        
        $('#imovel_endereco').val(enderecoCompleto);
        $('#imovel_tipo').val(tipo);
        
        // Formatar o valor do aluguel
        var valorFormatado = parseFloat(valorAluguel).toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        $('#valor_aluguel').val(valorFormatado);
        
        console.log("Imóvel alterado. Endereço:", enderecoCompleto, "Valor:", valorFormatado);
    });
    
    // Lançar os eventos onchange iniciais para preencher os campos - com timeout para garantir carregamento
    setTimeout(function() {
        console.log("Disparando eventos iniciais");
        
        if ($('#tomador_id').length > 0) {
            $('#tomador_id').trigger('change');
        }
        
        if ($('#inquilino_id').length > 0) {
            $('#inquilino_id').trigger('change');
        }
        
        if ($('#imovel_id').length > 0) {
            $('#imovel_id').trigger('change');
        }
    }, 500);
    
    // Adicionar efeitos visuais aos cards
    $('.card').hover(
        function() { $(this).addClass('shadow-sm'); },
        function() { $(this).removeClass('shadow-sm'); }
    );
    
    // Adicionar tooltips para botões e ícones
    $('[data-toggle="tooltip"]').tooltip();
    
    // Pré-carregar ícones para não haver atraso na animação
    $('<i class="fas fa-spinner fa-spin" style="display:none"></i>').appendTo('body');
});
