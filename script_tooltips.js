	// Inicializar DataTable com configurações completas
	$(document).ready(function() {
		// Inicializar tooltips do Bootstrap
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
			return new bootstrap.Tooltip(tooltipTriggerEl)
		});

		// Verificar se a tabela existe
		if ($('table').length === 0) {
			console.error('Nenhuma tabela encontrada na página');
			return;
		}

		// Inicializar DataTable com configurações completas
		var table = $('table').DataTable({
			"language": {
				...portugueseLanguage, // Spread operator para adicionar todas as traduções
				"searchPlaceholder": "Filtrar"
			},
			"order": [[1, "desc"]], // Ordenar por data
			"responsive": true,
			"pageLength": 25,
			// Configurações adicionais para melhorar a experiência
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
			"columnDefs": [
				{ 
					"targets": -1, // Última coluna (ações)
					"orderable": false, // Desabilitar ordenação na coluna de ações
					"searchable": false 
				}
			],
			"drawCallback": function() {
				$('.dataTables_wrapper').addClass('p-3');
				
				// Reinicializar tooltips após redraw da tabela
				var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
				var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
					return new bootstrap.Tooltip(tooltipTriggerEl)
				});
			}
		});