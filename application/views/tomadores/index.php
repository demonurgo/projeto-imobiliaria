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
                    <h5 class="card-title mb-0"><i class="fas fa-user-tie"></i> Tomadores</h5>
                    <div>
                        <a href="<?= base_url('tomadores/create') ?>" class="btn btn-light btn-sm">
                            <i class="fas fa-plus"></i> Novo Tomador
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
                        <table class="table table-striped table-hover" id="tomadoresTable">
                            <thead class="table-light">
                                <tr>

                                    <th>Nome/Razão Social</th>
                                    <th>CPF/CNPJ</th>
                                    <th>Email</th>
                                    <th>Telefone</th>
                                    <th>Imóveis Vinculados</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($tomadores)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">Nenhum tomador cadastrado.</td>
                                </tr>
                                <?php else: ?>
                                <?php foreach($tomadores as $tomador): ?>
                                <tr>

                                    <td><?= $tomador['razao_social'] ?></td>
                                    <td>
                                        <?php 
                                        $doc = $tomador['cpf_cnpj'];
                                        if(strlen($doc) === 11) {
                                            echo mask($doc, '###.###.###-##');
                                        } else if(strlen($doc) === 14) {
                                            echo mask($doc, '##.###.###/####-##');
                                        } else {
                                            echo $doc;
                                        }
                                        ?>
                                    </td>
                                    <td><?= $tomador['email'] ?? '-' ?></td>
                                    <td><?= $tomador['telefone'] ?? '-' ?></td>
                                    <td class="text-center">
                                        <?php 
                                        $CI =& get_instance();
                                        $CI->load->model('Imovel_model');
                                        $imoveis = $CI->Imovel_model->get_by_tomador($tomador['id']);
                                        $count = count($imoveis);
                                        if ($count > 0) {
                                            echo '<a href="' . base_url('tomadores/view/' . $tomador['id']) . '" class="badge bg-primary" title="Ver imóveis vinculados">' . $count . ' imóvel(is)</a>';
                                        } else {
                                            echo '<span class="badge bg-secondary">Nenhum</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="<?= base_url('tomadores/view/'.$tomador['id']) ?>" class="btn btn-info" title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= base_url('tomadores/edit/'.$tomador['id']) ?>" class="btn btn-primary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('tomadores/delete/'.$tomador['id']) ?>" class="btn btn-danger" title="Excluir" 
                                               onclick="return confirm('Tem certeza que deseja excluir este tomador?');">
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

<?php
// Função para formatar CPF/CNPJ
function mask($val, $mask) {
    $maskared = '';
    $k = 0;
    for($i = 0; $i <= strlen($mask)-1; $i++) {
        if($mask[$i] == '#') {
            if(isset($val[$k])) {
                $maskared .= $val[$k++];
            }
        } else {
            if(isset($mask[$i])) {
                $maskared .= $mask[$i];
            }
        }
    }
    return $maskared;
}
?>

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
