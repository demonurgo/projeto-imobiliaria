<div class="container-fluid">

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.16/dist/jquery.mask.min.js"></script>
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo site_url('dashboard'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo site_url('dimob'); ?>">DIMOB</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Notas Fiscais - <?php echo $ano; ?></li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-list-alt mr-2"></i>Notas Fiscais para DIMOB - Ano <?php echo $ano; ?>
                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo site_url('dimob'); ?>" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($this->session->flashdata('success')): ?>
                        <div class="alert alert-success">
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($erros) || !empty($inconsistencias)): ?>
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card card-danger card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">Erros e Inconsistências Encontrados</h3>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-warning">
                                        <h5><i class="icon fas fa-exclamation-triangle"></i> Atenção!</h5>
                                        <p>Foram encontrados erros e inconsistências que podem impedir a geração correta do arquivo DIMOB. Recomendamos corrigir esses problemas antes de gerar o arquivo.</p>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Nota Fiscal</th>
                                                    <th>Problemas</th>
                                                    <th>Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                // Mostrar erros
                                                foreach ($erros as $nota_id => $erro): 
                                                    // Verificar se já está nas inconsistências
                                                    if (isset($inconsistencias[$nota_id]) && 
                                                        $inconsistencias[$nota_id]['tipo'] == 'cpf_igual') {
                                                        $tem_outros_erros = false;
                                                        foreach ($erro['erros'] as $mensagem) {
                                                            if (strpos($mensagem, 'CPF/CNPJ do tomador') === false) {
                                                                $tem_outros_erros = true;
                                                                break;
                                                            }
                                                        }
                                                        
                                                        if (!$tem_outros_erros) {
                                                            continue;
                                                        }
                                                    }
                                                    
                                                    $numero_nota = $erro['numero'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $numero_nota; ?></td>
                                                    <td>
                                                        <ul class="mb-0">
                                                            <?php foreach ($erro['erros'] as $mensagem): ?>
                                                            <li><?php echo $mensagem; ?></li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo site_url('dimob/editar_nota/'.$nota_id); ?>" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-edit"></i> Editar
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                                
                                                <?php 
                                                // Mostrar inconsistências
                                                foreach ($inconsistencias as $nota_id => $inconsistencia): 
                                                    $numero_nota = '';
                                                    foreach ($notas as $nota) {
                                                        if ($nota['id'] == $nota_id) {
                                                            $numero_nota = $nota['numero'];
                                                            break;
                                                        }
                                                    }
                                                ?>
                                                <tr>
                                                    <td><?php echo $numero_nota; ?></td>
                                                    <td>
                                                    <div class="small">
                                                        <strong>CPF/CNPJ do Tomador:</strong> 
                                                        <span class="text-danger font-weight-bold"><?php echo $inconsistencia['tomador_cpf_cnpj']; ?></span>
                                                        é igual ao <strong>CPF/CNPJ do Inquilino:</strong> 
                                                        <span class="text-danger font-weight-bold"><?php echo $inconsistencia['inquilino_cpf_cnpj']; ?></span>
                                                    </div>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo site_url('dimob/editar_nota/'.$nota_id); ?>" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-edit"></i> Editar
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="table-notas" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nota Fiscal</th>
                                            <th>Data Emissão</th>
                                            <th>Competência</th>
                                            <th>Tomador</th>
                                            <th>CPF/CNPJ Tomador</th>
                                            <th>Inquilino</th>
                                            <th>CPF/CNPJ Inquilino</th>
                                            <th>Imóvel</th>
                                            <th>Tipo Imóvel</th>
                                            <th>Valor Aluguel</th>
                                            <th>Valor Serviço</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($notas)): ?>
                                            <?php foreach ($notas as $nota): ?>
                                            <?php 
                                            // Verificar se a nota tem erro ou inconsistência ou foi corrigida
                                            $tem_erro = isset($erros[$nota['id']]);
                                            $tem_inconsistencia = isset($inconsistencias[$nota['id']]);
                                            
                                            // Definir a classe da linha
                                            if ($tem_erro) {
                                                $classe_linha = 'table-danger';
                                            } elseif ($tem_inconsistencia) {
                                                $classe_linha = 'table-warning';
                                            } else {
                                                $classe_linha = ''; // Sem cor para notas sem problemas
                                            }
                                            
                                            // Verificar se CPF/CNPJ deve ser destacado em vermelho
                                            $tomador_cpf_em_vermelho = $tem_inconsistencia;
                                            $inquilino_cpf_em_vermelho = $tem_inconsistencia;
                                            ?>
                                            <tr class="<?php echo $classe_linha; ?>">
                                                <td><?php echo $nota['numero']; ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($nota['data_emissao'])); ?></td>
                                                <td><?php echo date('m/Y', strtotime($nota['competencia'])); ?></td>
                                                <td><?php echo $nota['tomador_nome']; ?></td>
                                                <td>
                                                    <?php if ($tomador_cpf_em_vermelho): ?>
                                                    <span class="text-danger font-weight-bold"><?php echo $nota['tomador_cpf_cnpj']; ?></span>
                                                    <?php else: ?>
                                                    <?php echo $nota['tomador_cpf_cnpj']; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo $nota['inquilino_nome']; ?></td>
                                                <td>
                                                    <?php if ($inquilino_cpf_em_vermelho): ?>
                                                    <span class="text-danger font-weight-bold"><?php echo $nota['inquilino_cpf_cnpj']; ?></span>
                                                    <?php else: ?>
                                                    <?php echo $nota['inquilino_cpf_cnpj']; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo $nota['imovel_endereco']; ?></td>
                                                <td><?php echo $nota['tipo_imovel'] == 'RURAL' ? 'Rural' : 'Urbano'; ?></td>
                                                <td>R$ <?php echo number_format($nota['valor_aluguel'], 2, ',', '.'); ?></td>
                                                <td>R$ <?php echo number_format($nota['valor_servicos'], 2, ',', '.'); ?></td>
                                                <td>
                                                    <a href="<?php echo site_url('dimob/editar_nota/'.$nota['id']); ?>" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="12" class="text-center">Nenhuma nota fiscal encontrada para o ano <?php echo $ano; ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <?php if (!empty($notas)): ?>
                                <div class="card card-success card-outline">
                                    <div class="card-header bg-light">
                                        <h3 class="card-title"><i class="fas fa-file-download mr-2 text-success"></i>Gerar Arquivo DIMOB</h3>
                                    </div>
                                    <div class="card-body">
                                        <?php echo form_open('dimob/gerar', ['class' => 'form-horizontal']); ?>
                                            <input type="hidden" name="ano" value="<?php echo $ano; ?>">
                                            
                                            <?php if (!empty($erros)): ?>
                                                <input type="hidden" name="confirma" value="0">
                                                <button type="button" class="btn btn-warning btn-lg" data-toggle="modal" data-target="#modalConfirm">
                                                    <i class="fas fa-file-download mr-2"></i> Gerar Arquivo DIMOB (Com Erros)
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" class="btn btn-success btn-lg px-5">
                                                    <i class="fas fa-file-download mr-2"></i> Gerar Arquivo DIMOB
                                                </button>
                                            <?php endif; ?>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            
                                <?php if (!empty($erros)): ?>
                                <!-- Modal de confirmação para geração com erros -->
                                <div class="modal fade" id="modalConfirm" tabindex="-1" role="dialog" aria-labelledby="modalConfirmLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning">
                                                <h5 class="modal-title" id="modalConfirmLabel"><i class="fas fa-exclamation-triangle mr-2"></i>Atenção: Arquivo com Erros</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-danger">
                                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                                    <span>O arquivo DIMOB será gerado com erros que podem causar rejeição pela Receita Federal.</span>
                                                </div>
                                                
                                                <p>Deseja continuar mesmo assim?</p>
                                                <p class="font-weight-bold text-danger">Recomendamos fortemente corrigir os erros antes de gerar o arquivo.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    <i class="fas fa-times mr-2"></i>Cancelar
                                                </button>
                                                <?php echo form_open('dimob/gerar', ['class' => 'form-horizontal']); ?>
                                                    <input type="hidden" name="ano" value="<?php echo $ano; ?>">
                                                    <input type="hidden" name="confirma" value="1">
                                                    <button type="submit" class="btn btn-warning">
                                                        <i class="fas fa-exclamation-triangle mr-2"></i>Gerar Mesmo Assim
                                                    </button>
                                                <?php echo form_close(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos mínimos necessários */
    .text-danger.font-weight-bold {
        position: relative;
        background-color: rgba(220, 53, 69, 0.1);
        padding: 2px 4px;
        border-radius: 3px;
    }
</style>

<script>

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
		// Verificar se já existe o DataTable inicializado
		if($.fn.dataTable.isDataTable('#table-notas')) {
			$('#table-notas').DataTable().destroy();
		}
		
		// Inicializar DataTable com configurações completas
		var table = $('#table-notas').DataTable({
			"language": portugueseLanguage,
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
		$('#table-notas').addClass('table-bordered table-spacing');

		// Debug para verificar se o DataTables foi inicializado corretamente
		console.log('DataTable inicializada');
		console.log('Número de linhas:', table.rows().count());
	});
</script>
