<style>
    .table-spacing tbody tr {
        height: 60px;
    }
    .table-spacing td {
        vertical-align: middle;
        padding: 10px !important;
    }
    .dataTables_wrapper {
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 5px;
    }
</style>
<div class="container">
	<!-- Carregar jQuery no topo da página -->
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.16/dist/jquery.mask.min.js"></script>

	<div class="row mb-4">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
					<h5 class="card-title mb-0"><i class="fas fa-file-invoice"></i> Notas Fiscais</h5>
					<a href="<?= base_url('importacao') ?>" class="btn btn-success btn-sm">
						<i class="fas fa-plus"></i> Importar Novas Notas
					</a>
				</div>
				<!--
				<div class="card-body">
					 Painel de Filtros 
					<div class="card mb-4">
						<div class="card-header bg-light">
							<a class="text-dark" data-bs-toggle="collapse" href="#collapseFilterPanel" role="button" aria-expanded="true" aria-controls="collapseFilterPanel">
								<i class="fas fa-filter"></i> Filtros Avançados <i class="fas fa-chevron-up small"></i>
							</a>
						</div>
						<div class="collapse show" id="collapseFilterPanel">
							<div class="card-body">
								<form id="filter-form" action="<?= base_url('notas/filter') ?>" method="post" target="_blank">
									<div class="row g-3">
										<div class="col-md-4 col-lg-3">
											<label for="filter_numero" class="form-label">N°</label>
											<input type="text" class="form-control form-control-sm filter-input" id="filter_numero" name="numero" placeholder="Número da nota">
										</div>
										<div class="col-md-4 col-lg-3">
											<label for="filter_data_emissao" class="form-label">Data Emissão</label>
											<input type="date" class="form-control form-control-sm filter-input" id="filter_data_emissao" name="data_emissao">
										</div>
										<div class="col-md-4 col-lg-3">
											<label for="filter_valor" class="form-label">Valor do Serviço</label>
											<input type="text" class="form-control form-control-sm filter-input" id="filter_valor" name="valor" placeholder="Valor">
										</div>
										<div class="col-md-4 col-lg-3">
											<label for="filter_prestador" class="form-label">Prestador</label>
											<input type="text" class="form-control form-control-sm filter-input" id="filter_prestador" name="prestador" placeholder="Nome do prestador">
										</div>
										<div class="col-md-4 col-lg-3">
											<label for="filter_tomador" class="form-label">Tomador</label>
											<input type="text" class="form-control form-control-sm filter-input" id="filter_tomador" name="tomador" placeholder="Nome do tomador">
										</div>
										<div class="col-md-4 col-lg-3">
											<label for="filter_inquilino" class="form-label">Inquilino</label>
											<input type="text" class="form-control form-control-sm filter-input" id="filter_inquilino" name="inquilino" placeholder="Nome do inquilino">
										</div>
										<div class="col-md-4 col-lg-3">
											<label for="filter_dimob" class="form-label">DIMOB</label>
											<select class="form-select form-select-sm filter-input" id="filter_dimob" name="dimob">
												<option value="">Todos</option>
												<option value="1">Incluído</option>
												<option value="0">Pendente</option>
											</select>
										</div>
										<div class="col-md-4 col-lg-3">
											<label for="filter_status" class="form-label">Status</label>
											<select class="form-select form-select-sm filter-input" id="filter_status" name="status">
												<option value="">Todos</option>
												<option value="importado">Importado</option>
												<option value="processado">Processado</option>
												<option value="atualizado">Atualizado</option>
												<option value="cancelado">Cancelado</option>
											</select>
										</div>
										<div class="col-12 text-center">
											<div class="btn-group">
												<a href="<?= base_url('notas/teste_filtro') ?>" target="_blank" class="btn btn-info btn-sm">
													<i class="fas fa-bug"></i> Testar Conexão
												</a>
												<button type="submit" class="btn btn-primary btn-sm">
													<i class="fas fa-search"></i> Modo Normal
												</button>
												<button type="button" id="btn-apply-filters" class="btn btn-primary btn-sm">
													<i class="fas fa-sync"></i> Modo AJAX
												</button>
												<button type="reset" class="btn btn-outline-secondary btn-sm">
													<i class="fas fa-eraser"></i> Limpar
												</button>
											</div>
											<span id="filterLoadingIndicator" style="display:none; margin-left:10px;">
												<i class="fas fa-spinner fa-spin"></i> Filtrando...
											</span>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
					-->
			<!-- Fim do Painel de Filtros -->

			<div class="table-responsive">
				<table class="table table-striped table-hover table-sm" id="notasTable">
					<thead class="table-light">
						<tr>
							<th>N° Nota</th>
							<th>Data Emissão</th>
							<th>Locador (Proprietário)</th>
							<th>Locatário (Inquilino)</th>
							<th>CPF/CNPJ Locatário</th>
							<th>Endereço Imóvel</th>
							<th>Valor Aluguel</th>
							<th>Valor Comissão</th>
							<th style="width: 100px;">Ações</th>
						</tr>
					</thead>
					<tbody>
						<?php if (empty($notas)): ?>
							<tr>
								<td colspan="9" class="text-center">Nenhuma nota fiscal encontrada.</td>
							</tr>
						<?php else: ?>
							<?php foreach ($notas as $nota): ?>
								<tr>
									<td>
										<?= $nota['numero'] ?>
										<?php if(isset($nota['editado_manualmente']) && $nota['editado_manualmente'] == 1): ?>
											<span class="badge bg-info" title="Editado manualmente"><i class="fas fa-user-edit"></i></span>
										<?php endif; ?>
									</td>
									<td><?= date('d/m/Y', strtotime($nota['data_emissao'])) ?></td>
									<td><?= $nota['tomador_nome'] ?></td>
									<td>
										<?php if ($nota['inquilino_id'] && isset($nota['inquilino_nome'])): ?>
											<?= $nota['inquilino_nome'] ?>
										<?php else: ?>
											<span class="badge bg-warning text-dark">Não identificado</span>
										<?php endif; ?>
									</td>
									<td>
										<?php if ($nota['inquilino_id'] && isset($nota['inquilino_cpf_cnpj'])): ?>
											<?php 
											$cpf_cnpj_duplicado = false;
											if (isset($nota['tomador_cpf_cnpj']) && isset($nota['inquilino_cpf_cnpj']) && 
												!empty($nota['tomador_cpf_cnpj']) && !empty($nota['inquilino_cpf_cnpj']) && 
												$nota['tomador_cpf_cnpj'] === $nota['inquilino_cpf_cnpj']) {
												$cpf_cnpj_duplicado = true;
											}
											?>
											<span <?= $cpf_cnpj_duplicado ? 'class="text-danger fw-bold"' : '' ?>>
												<?= $nota['inquilino_cpf_cnpj'] ?>
												<?php if($cpf_cnpj_duplicado): ?>
													<i class="fas fa-exclamation-triangle text-danger" title="CPF/CNPJ do inquilino igual ao do proprietário!"></i>
												<?php endif; ?>
											</span>
										<?php else: ?>
											<span class="badge bg-warning text-dark">Não identificado</span>
										<?php endif; ?>
									</td>
									<td>
										<?php if ($nota['imovel_id'] && isset($nota['imovel_endereco'])): ?>
											<?= $nota['imovel_endereco'] ?>
										<?php else: ?>
											<span class="badge bg-warning text-dark">Não identificado</span>
										<?php endif; ?>
									</td>
									<td class="text-end">
										<?php if ($nota['imovel_id'] && isset($nota['valor_aluguel'])): ?>
											R$ <?= number_format($nota['valor_aluguel'], 2, ',', '.') ?>
										<?php else: ?>
											<span class="badge bg-warning text-dark">Não definido</span>
										<?php endif; ?>
									</td>
									<td class="text-end">R$ <?= number_format($nota['valor_servicos'], 2, ',', '.') ?></td>
									<td>
										<div class="btn-group btn-group-sm" role="group">
											<a href="<?= base_url('notas/view/' . $nota['id']) ?>" class="btn btn-info" title="Visualizar">
												<i class="fas fa-eye"></i>
											</a>
											<a href="<?= base_url('notas/edit/' . $nota['id']) ?>" class="btn btn-primary" title="Editar">
												<i class="fas fa-edit"></i>
											</a>
											<a href="<?= base_url('notas/delete/' . $nota['id']) ?>" class="btn btn-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir esta nota fiscal?');">
												<i class="fas fa-trash"></i>
											</a>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</div>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header bg-info text-white">
						<h5 class="card-title mb-0"><i class="fas fa-info-circle"></i> Informações</h5>
					</div>
					<div class="card-body">
						<h6>Informações para DIMOB:</h6>
						<div class="mb-3">
						<ul>
						<li><strong>Locador (Proprietário):</strong> Pessoa/empresa proprietária do imóvel</li>
						<li><strong>Locatário (Inquilino):</strong> Pessoa que aluga o imóvel</li>
						<li><strong>Endereço do Imóvel:</strong> Localização do imóvel alugado</li>
						<li><strong>Valor Aluguel:</strong> Valor mensal pago pelo inquilino</li>
						<li><strong>Valor Comissão:</strong> Valor recebido pela imobiliária como taxa de administração</li>
						</ul>
						</div>
					
					<h6>Legenda de Indicações:</h6>
					<div class="mb-3">
					<span class="badge bg-warning text-dark">Não identificado</span> - Informação pendente que precisa ser complementada.
					<br>
					<span class="badge bg-info"><i class="fas fa-user-edit"></i></span> - Nota fiscal que foi editada manualmente.
					<br>
					<span class="text-danger fw-bold">CPF/CNPJ em vermelho <i class="fas fa-exclamation-triangle text-danger"></i></span> - O CPF/CNPJ do inquilino é igual ao do proprietário, o que pode indicar um erro de cadastro.
					</div>

						<div class="alert alert-warning">
						 <i class="fas fa-exclamation-triangle"></i> <strong>Atenção:</strong>
						Notas fiscais sem inquilino identificado precisam ser editadas antes de gerar o arquivo DIMOB.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	// Verificar se jQuery está disponível
	if (typeof jQuery === 'undefined') {
		console.error('jQuery não está carregado!');
		alert('Erro: jQuery não está carregado. Contate o administrador.');
	} else {
		console.log('jQuery está carregado, versão:', jQuery.fn.jquery);
	}

		// Função de filtro
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

	// Inicializar DataTable com configurações completas
	$(document).ready(function() {
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
			}
		});

		// Adicionar classes para melhorar o estilo
		$('table').addClass('table-bordered table-spacing');

		// Debug para verificar se o DataTables foi inicializado corretamente
		console.log('DataTable inicializada');
		console.log('Número de linhas:', table.rows().count());
	});

		/*// Verificar se o botão existe
		if ($('#btn-apply-filters').length === 0) {
			console.error('Botão de aplicar filtros não encontrado!');
		}

		// Botão para aplicar os filtros - usando método on
		$(document).on('click', '#btn-apply-filters', function(e) {
			e.preventDefault();
			console.log('Botão de filtro clicado');

			// Mostrar carregamento
			$('#filterLoadingIndicator').show();

			// Coletar valores dos filtros
			var filters = {
				numero: $('#filter_numero').val(),
				data_emissao: $('#filter_data_emissao').val(),
				prestador: $('#filter_prestador').val(),
				tomador: $('#filter_tomador').val(),
				valor: $('#filter_valor').val().trim(),
				inquilino: $('#filter_inquilino').val(),
				dimob: $('#filter_dimob').val(),
				status: $('#filter_status').val()
			};

			console.log('Filtros coletados:', filters);
			console.log('URL do AJAX:', '<?= base_url("notas/filter") ?>');

			// Enviar requisição AJAX para o servidor
			$.ajax({
				url: '<?= base_url("notas/filter") ?>',
				type: 'POST',
				data: filters,
				dataType: 'json',
				beforeSend: function() {
					console.log('AJAX sending request...');
				},
				success: function(response) {
					console.log('AJAX success response:', response);
					// Limpar a tabela atual
					table.clear();

					// Para cada item no resultado, adicionar uma linha na tabela
					if (response.data && response.data.length > 0) {
						console.log('Total de notas encontradas:', response.data.length);
						$(response.data).each(function(index, nota) {
							// Formatar a data (2024-12-30T15:45:44 -> 30/12/2024 15:45)
							var date = new Date(nota.data_emissao);
							var formattedDate = date.getDate().toString().padStart(2, '0') + '/' +
								(date.getMonth() + 1).toString().padStart(2, '0') + '/' +
								date.getFullYear() + ' ' +
								date.getHours().toString().padStart(2, '0') + ':' +
								date.getMinutes().toString().padStart(2, '0');

							// Formatar valor numérico para exibição
							var formattedValue = parseFloat(nota.valor_servicos).toLocaleString('pt-BR', {
								minimumFractionDigits: 2,
								maximumFractionDigits: 2
							});

							// Formatar status DIMOB
							var dimobStatus = (nota.dimob_enviado == 1) ?
								'<span class="badge bg-success">Incluído</span>' :
								'<span class="badge bg-secondary">Pendente</span>';

							// Formatar status da nota
							var notaStatus = '';
							switch (nota.status) {
								case 'importado':
									notaStatus = '<span class="badge bg-info">Importado</span>';
									break;
								case 'processado':
									notaStatus = '<span class="badge bg-success">Processado</span>';
									break;
								case 'atualizado':
									notaStatus = '<span class="badge bg-primary">Atualizado</span>';
									break;
								case 'cancelado':
									notaStatus = '<span class="badge bg-danger">Cancelado</span>';
									break;
								default:
									notaStatus = '<span class="badge bg-secondary">Desconhecido</span>';
							}

							// Formatar inquilino
							var inquilinoDisplay = nota.inquilino_nome ?
								nota.inquilino_nome :
								'<span class="badge bg-warning text-dark">Não identificado</span>';

							// Formatar botões de ação
							var actions = '<div class="btn-group btn-group-sm" role="group">' +
								'<a href="<?= base_url("notas/view/") ?>' + nota.id + '" class="btn btn-info" title="Visualizar">' +
								'<i class="fas fa-eye"></i>' +
								'</a>' +
								'<a href="<?= base_url("notas/edit/") ?>' + nota.id + '" class="btn btn-primary" title="Editar">' +
								'<i class="fas fa-edit"></i>' +
								'</a>';

							// Botão de status DIMOB
							if (nota.dimob_enviado == 0) {
								actions += '<a href="<?= base_url("notas/dimob/") ?>' + nota.id + '/1" class="btn btn-success" title="Marcar para DIMOB">' +
									'<i class="fas fa-check"></i>' +
									'</a>';
							} else {
								actions += '<a href="<?= base_url("notas/dimob/") ?>' + nota.id + '/0" class="btn btn-warning" title="Remover da DIMOB">' +
									'<i class="fas fa-times"></i>' +
									'</a>';
							}

							// Botão de exclusão
							actions += '<a href="<?= base_url("notas/delete/") ?>' + nota.id + '" class="btn btn-danger" title="Excluir" onclick="return confirm(\'Tem certeza que deseja excluir esta nota fiscal?\');">' +
								'<i class="fas fa-trash"></i>' +
								'</a>' +
								'</div>';

							// Adicionar a linha à tabela
							table.row.add([
								nota.numero,
								formattedDate,
								nota.prestador_nome || '',
								nota.tomador_nome || '',
								formattedValue,
								inquilinoDisplay,
								dimobStatus,
								notaStatus,
								actions
							]);
						});
					} else {
						console.log('Nenhum resultado encontrado');
					}

					// Redesenhar a tabela com os novos dados
					table.draw();

					// Esconder indicador de carregamento
					$('#filterLoadingIndicator').hide();
				},
				error: function(xhr, status, error) {
					console.error('AJAX error response:');
					console.error('Status:', status);
					console.error('Error:', error);
					console.error('Response Text:', xhr.responseText);
					console.error('Status Code:', xhr.status);
					console.error('XHR:', xhr);

					// Mostrar erro para o usuário
					alert('Ocorreu um erro ao filtrar as notas: ' + error + ' (' + xhr.status + ')');
					$('#filterLoadingIndicator').hide();
				},
				complete: function() {
					console.log('AJAX request complete');
				}
			});
		});

		// Botão para limpar todos os filtros - usando método on
		$(document).on('click', '#btn-clear-filters', function(e) {
			e.preventDefault();
			console.log('Botão limpar filtros clicado');

			// Limpar todos os campos de filtro
			$('.filter-input').val('');
			$('#filter_dimob, #filter_status').val('');

			// Recarregar a página para mostrar todas as notas novamente
			window.location.href = '<?= base_url("notas") ?>';
		});

		// Pressionar Enter em qualquer campo de filtro também aplica os filtros
		$('.filter-input').keypress(function(e) {
			if (e.which == 13) { // 13 = Enter key
				console.log('Enter pressionado em campo de filtro');
				$('#btn-apply-filters').trigger('click');
			}
		});*/
</script>