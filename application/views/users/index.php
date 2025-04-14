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
                    <h5 class="card-title mb-0"><i class="fas fa-users"></i> Gerenciar Usuários</h5>
                    <?php if ($this->session->userdata('is_admin') == 1): ?>
                    <a href="<?php echo base_url('users/create'); ?>" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Novo Usuário
                    </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (empty($users)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Nenhum usuário cadastrado.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>

                                        <th>Usuário</th>
                                        <th>Nome</th>
                                        <th>E-mail</th>
                                        <th>Tipo</th>
                                        <th>Último Login</th>
                                        <th>Criado em</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo $user->username; ?></td>
                                            <td><?php echo $user->name; ?></td>
                                            <td><?php echo $user->email; ?></td>
                                            <td>
                                                <?php if ($user->is_admin == 1): ?>
                                                    <span class="badge bg-danger">Administrador</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Usuário</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if ($user->last_login) {
                                                        echo date('d/m/Y H:i', strtotime($user->last_login));
                                                    } else {
                                                        echo '<span class="text-muted">Nunca</span>';
                                                    }
                                                ?>
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($user->created_at)); ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <?php 
                                                    $is_current_user = ($this->session->userdata('user_id') == $user->id);
                                                    $is_admin = ($this->session->userdata('is_admin') == 1);
                                                    
                                                    // Botão de editar - usuário atual só pode editar ele mesmo ou outros se for admin
                                                    if ($is_current_user || $is_admin): 
                                                    ?>
                                                    <a href="<?php echo base_url('users/edit/'.$user->id); ?>" class="btn btn-primary" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php 
                                                    // Botão de excluir - só admin pode ver e não pode excluir a si mesmo
                                                    if ($is_admin && !$is_current_user): 
                                                    ?>
                                                        <a href="<?php echo base_url('users/delete/'.$user->id); ?>" class=" ml-3 btn btn-danger btn-delete" title="Excluir">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
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
