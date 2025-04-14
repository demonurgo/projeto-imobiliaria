
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
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.16/dist/jquery.mask.min.js"></script>
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-check-circle"></i> Revisão da Importação</h5>
                    <div>
                        <a href="<?= base_url('importacao/concluir/'.$batch_id) ?>" class="btn btn-success btn-sm">
                            <i class="fas fa-check"></i> Concluir Importação
                        </a>
                        <a href="<?= base_url('importacao') ?>" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <h3>Notas Fiscais Importadas</h3>
                    <p>Verifique e confirme as informações antes de concluir a importação.</p>
                    
                    <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php 
                    // Verificar se existem notas com CPF/CNPJ do tomador igual ao do inquilino
                    $notas_com_cpf_cnpj_duplicado = false;
                    foreach($notas as $nota) {
                        if(isset($nota['status']) && $nota['status'] === 'revisar') {
                            $notas_com_cpf_cnpj_duplicado = true;
                            break;
                        }
                    }
                    ?>
                    
                    <?php if($notas_com_cpf_cnpj_duplicado): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Atenção!</strong> Existem notas fiscais onde o CPF/CNPJ do tomador (proprietário) é igual ao do inquilino (locatário). Isso pode indicar um erro de cadastro. As notas com esta inconsistência estão destacadas em vermelho abaixo.
                    </div>
                    <?php endif; ?>
                    
                    <?php if(empty($notas)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Nenhuma nota fiscal foi importada com este lote.
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Número Nota</th>
                                    <th>Data Emissão</th>
                                    <th>Locador (Proprietário)</th>
                                    <th>CPF/CNPJ Proprietário</th>
                                    <th>Locatário (Inquilino)</th>
                                    <th>CPF/CNPJ Locatário</th>
                                    <th>Endereço Imóvel</th>
                                    <th>Valor Aluguel</th>
                                    <th>Valor Comissão</th>
                                    <th width="120">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($notas as $nota): ?>
                                <tr class="<?= (isset($nota['status']) && $nota['status'] === 'revisar') ? 'table-danger' : '' ?>">
                                    <td>
                                        <?= $nota['numero'] ?>
                                        <?php if(isset($nota['editado_manualmente']) && $nota['editado_manualmente'] == 1): ?>
                                            <span class="badge bg-info" title="Editado manualmente"><i class="fas fa-user-edit"></i></span>
                                        <?php endif; ?>
                                        <?php if(isset($nota['status']) && $nota['status'] === 'revisar'): ?>
                                            <span class="badge bg-danger" title="<?= htmlspecialchars($nota['observacoes']) ?>"><i class="fas fa-exclamation-triangle"></i> Verificar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($nota['data_emissao'])) ?></td>
                                    <td><?= $nota['tomador_nome'] ?></td>
                                    <td><?= isset($nota['proprietario_cpf']) ? $nota['proprietario_cpf'] : (isset($nota['tomador_cpf_cnpj']) ? $nota['tomador_cpf_cnpj'] : '<span class="badge bg-warning text-dark">Não informado</span>') ?></td>
                                    <td>
                                        <?php if($nota['inquilino_id'] && isset($nota['inquilino_nome'])): ?>
                                            <?= $nota['inquilino_nome'] ?>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Não identificado</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($nota['inquilino_id'] && isset($nota['inquilino_cpf_cnpj'])): ?>
                                            <?php 
                                            $cpf_cnpj_duplicado = false;
                                            if (isset($nota['tomador_cpf_cnpj']) && isset($nota['inquilino_cpf_cnpj']) && 
                                                !empty($nota['tomador_cpf_cnpj']) && !empty($nota['inquilino_cpf_cnpj']) && 
                                                $nota['tomador_cpf_cnpj'] === $nota['inquilino_cpf_cnpj']) {
                                                $cpf_cnpj_duplicado = true;
                                            }
                                            ?>
                                            <span <?= $cpf_cnpj_duplicado ? 'class="text-danger fw-bold" data-bs-toggle="tooltip" data-bs-placement="top" title="ATENÇÃO: O CPF/CNPJ do inquilino é igual ao do proprietário! Isso pode indicar um erro de cadastro."' : '' ?>>
                                                <?= $nota['inquilino_cpf_cnpj'] ?>
                                                <?php if($cpf_cnpj_duplicado): ?>
                                                    <i class="fas fa-exclamation-triangle text-danger"></i>
                                                <?php endif; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Não identificado</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($nota['imovel_id'] && isset($nota['imovel_endereco'])): ?>
                                            <?= $nota['imovel_endereco'] ?>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Não identificado</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($nota['imovel_id'] && isset($nota['valor_aluguel'])): ?>
                                            R$ <?= number_format($nota['valor_aluguel'], 2, ',', '.') ?>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Não definido</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>R$ <?= number_format($nota['valor_servicos'], 2, ',', '.') ?></td>
                                    <td>
                                        <a href="<?= base_url('importacao/editar/'.$nota['id']) ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <a href="<?= base_url('importacao/excluir/'.$nota['id']) ?>" class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Tem certeza que deseja excluir esta nota?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php if(isset($nota['status']) && $nota['status'] === 'revisar' && !empty($nota['observacoes'])): ?>
                                <tr class="table-danger">
                                    <td colspan="9" class="text-danger">
                                        <i class="fas fa-exclamation-triangle"></i> <strong>Atenção:</strong> <?= $nota['observacoes'] ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <span class="badge bg-info"><i class="fas fa-user-edit"></i></span> Nota editada manualmente
                        </small>
                        <?php if($notas_com_cpf_cnpj_duplicado): ?>
                        <br>
                        <small class="text-danger">
                            <i class="fas fa-exclamation-triangle"></i> Linhas em vermelho indicam notas onde o CPF/CNPJ do tomador é igual ao do inquilino
                        </small>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-info-circle"></i> Instruções</h5>
                </div>
                <div class="card-body">
                    <ol>
                        <li><strong>Verifique</strong> se todas as informações necessárias para o DIMOB foram importadas corretamente.</li>
                        <li><strong>Edite</strong> as notas fiscais que precisam de correções ou complementos.</li>
                        <li>Certifique-se de que cada nota possui o <strong>locatário (inquilino) identificado</strong> com CPF/CNPJ e o <strong>valor do aluguel</strong> corretamente informado.</li>
                        <li>Após confirmar que tudo está correto, <strong>clique em "Concluir Importação"</strong> para finalizar o processo.</li>
                    </ol>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Atenção:</strong> Todas as informações exibidas na tabela acima são essenciais para a geração correta do arquivo DIMOB.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

// Adicionar links para DataTables e CSS
// Modificar a tabela para usar DataTables
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

// Inicializar DataTable e tooltips
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
        }
    });

    // Adicionar classes para melhorar o estilo
    $('table').addClass('table-bordered table-spacing');

    // Debug para verificar se o DataTables foi inicializado corretamente
    console.log('DataTable inicializada');
    console.log('Número de linhas:', table.rows().count());
});
</script>
