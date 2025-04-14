
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
                    
                    <?php if(empty($notas)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Nenhuma nota fiscal foi importada com este lote.
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Número</th>
                                    <th>Data Emissão</th>
                                    <th>Prestador</th>
                                    <th>Tomador</th>
                                    <th>Inquilino</th>
                                    <th>Valor (R$)</th>
                                    <th width="150">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($notas as $nota): ?>
                                <tr>
                                    <td>
                                        <?= $nota['numero'] ?>
                                        <?php if(isset($nota['editado_manualmente']) && $nota['editado_manualmente'] == 1): ?>
                                            <span class="badge bg-info" title="Editado manualmente"><i class="fas fa-user-edit"></i></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($nota['data_emissao'])) ?></td>
                                    <td><?= $nota['prestador_nome'] ?></td>
                                    <td><?= $nota['tomador_nome'] ?></td>
                                    <td>
                                        <?php if($nota['inquilino_id'] && isset($nota['inquilino_nome'])): ?>
                                            <?= $nota['inquilino_nome'] ?>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Não identificado</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= number_format($nota['valor_servicos'], 2, ',', '.') ?></td>
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
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <span class="badge bg-info"><i class="fas fa-user-edit"></i></span> Nota editada manualmente
                        </small>
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
                        <li><strong>Verifique</strong> se todas as informações foram importadas corretamente.</li>
                        <li><strong>Edite</strong> as notas fiscais que precisam de correções ou complementos.</li>
                        <li>Se alguma nota tiver o inquilino <strong>não identificado</strong>, você deve editá-la e selecionar o inquilino correto.</li>
                        <li>Após confirmar que tudo está correto, <strong>clique em "Concluir Importação"</strong> para finalizar o processo.</li>
                    </ol>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Atenção:</strong> Após concluir a importação, as notas estarão disponíveis para geração do arquivo DIMOB.
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
