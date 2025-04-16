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
			<div class="table-responsive">
				<table class="table table-striped table-hover table-sm" id="notasTable">
					<thead class="table-light">
						<tr>
							<th>N° Nota</th>
							<th>Data Emissão</th>
							<th>Locador (Proprietário)</th>
							<th>CPF/CNPJ Proprietário</th>
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
									<td><?= isset($nota['tomador_cpf_cnpj']) ? $nota['tomador_cpf_cnpj'] : '<span class="badge bg-warning text-dark">Não informado</span>' ?></td>
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

</script>
