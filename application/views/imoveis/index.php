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
                    <h5 class="card-title mb-0"><i class="fas fa-building"></i> Imóveis</h5>
                    <div>
                        <a href="<?= base_url('imoveis/create') ?>" class="btn btn-light btn-sm">
                            <i class="fas fa-plus"></i> Novo Imóvel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                
                    <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    

                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="imoveisTable">
                            <thead class="table-light">
                                <tr>

                                    <th>Código Ref.</th>
                                    <th>Endereço</th>
                                    <th>Bairro</th>
                                    <th>Cidade/UF</th>
                                    <th>Inquilino</th>
                                    <th>Valor Aluguel</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($imoveis)): ?>
                                <tr>
                                    <td colspan="7" class="text-center">Nenhum imóvel cadastrado.</td>
                                </tr>
                                <?php else: ?>
                                <?php foreach($imoveis as $imovel): ?>
                                <tr>

                                    <td><?= $imovel['codigo_referencia'] ?? 'Não informado' ?></td>
                                    <td>
                                        <?= $imovel['endereco'] ?>
                                    </td>
                                    <td><?= $imovel['bairro'] ?? 'Não informado' ?></td>
                                    <td>
                                        <?php if(!empty($imovel['cidade']) || !empty($imovel['uf'])): ?>
                                            <?= $imovel['cidade'] ?>/<?= $imovel['uf'] ?>
                                        <?php else: ?>
                                            Não informado
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(!empty($imovel['inquilino_id']) && isset($imovel['inquilino_nome'])): ?>
                                            <a href="<?= base_url('inquilinos/view/'.$imovel['inquilino_id']) ?>" class="text-decoration-none">
                                                <?= $imovel['inquilino_nome'] ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Sem inquilino</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(!empty($imovel['valor_aluguel'])): ?>
                                            R$ <?= number_format($imovel['valor_aluguel'], 2, ',', '.') ?>
                                        <?php else: ?>
                                            Não informado
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="<?= base_url('imoveis/view/'.$imovel['id']) ?>" class="btn btn-info" title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= base_url('imoveis/edit/'.$imovel['id']) ?>" class="btn btn-primary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('imoveis/delete/'.$imovel['id']) ?>" class="btn btn-danger" title="Excluir" 
                                               onclick="return confirm('Tem certeza que deseja excluir este imóvel?');">
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
