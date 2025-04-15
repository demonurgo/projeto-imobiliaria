<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if (!is_admin()): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> Acesso restrito a administradores do sistema.
    </div>
    <?php return; ?>
<?php endif; ?>

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
                    <h5 class="card-title mb-0"><i class="fas fa-history"></i> Log de Atividades do Sistema</h5>
                    <div>
                        <a href="<?= base_url('logs/export?' . http_build_query($_GET)) ?>" class="btn btn-light btn-sm">
                            <i class="fas fa-file-excel"></i> Exportar Resultados
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Mensagens de Sistema -->
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

                    <!-- Filtros -->
                    <div class="card mb-4">
                        <div class="card-header bg-light d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
                            <button class="btn btn-link btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilters" aria-expanded="true" aria-controls="collapseFilters">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <div class="collapse show" id="collapseFilters">
                            <div class="card-body">
                                <form method="get" action="<?= base_url('logs') ?>" id="filter-form">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="user_id">Usuário</label>
                                            <select name="user_id" id="user_id" class="form-select select2">
                                                <option value="">Todos</option>
                                                <?php foreach($users as $user_id => $user_name): ?>
                                                <option value="<?= $user_id ?>" <?= (isset($filters['user_id']) && $filters['user_id'] == $user_id) ? 'selected' : '' ?>>
                                                    <?= $user_name ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="module">Módulo</label>
                                            <select name="module" id="module" class="form-select select2">
                                                <option value="">Todos</option>
                                                <?php foreach($modules as $key => $name): ?>
                                                <option value="<?= $key ?>" <?= (isset($filters['module']) && $filters['module'] == $key) ? 'selected' : '' ?>>
                                                    <?= $name ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="action">Ação</label>
                                            <select name="action" id="action" class="form-select select2">
                                                <option value="">Todas</option>
                                                <?php foreach($actions as $key => $name): ?>
                                                <option value="<?= $key ?>" <?= (isset($filters['action']) && $filters['action'] == $key) ? 'selected' : '' ?>>
                                                    <?= $name ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="search">Pesquisar na descrição</label>
                                            <input type="text" name="search" id="search" class="form-control" value="<?= isset($filters['search']) ? $filters['search'] : '' ?>" placeholder="Termo de pesquisa...">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="date_start">Data Inicial</label>
                                            <input type="date" name="date_start" id="date_start" class="form-control" value="<?= isset($filters['date_start']) ? $filters['date_start'] : '' ?>">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="date_end">Data Final</label>
                                            <input type="date" name="date_end" id="date_end" class="form-control" value="<?= isset($filters['date_end']) ? $filters['date_end'] : '' ?>">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="batch_id">Batch ID (Importação)</label>
                                            <input type="text" name="batch_id" id="batch_id" class="form-control" value="<?= isset($filters['batch_id']) ? $filters['batch_id'] : '' ?>" placeholder="ID do lote...">
                                        </div>
                                        <div class="col-md-3 mb-3 d-flex align-items-end">
                                            <div class="btn-group w-100">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-filter"></i> Filtrar
                                                </button>
                                                <a href="<?= base_url('logs') ?>" class="btn btn-secondary">
                                                    <i class="fas fa-eraser"></i> Limpar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Resultados -->
                    <div class="card">
                        <div class="card-header bg-light d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">
                                Registros de Atividade
                                <?php if(isset($total_rows) && $total_rows > 0): ?>
                                <span class="badge bg-secondary"><?= $total_rows ?> registro(s)</span>
                                <?php endif; ?>
                            </h6>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cleanLogsModal">
                                <i class="fas fa-trash"></i> Limpar Logs Antigos
                            </button>
                        </div>
                        <div class="card-body">
                            <?php if (empty($logs)): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Nenhum registro de atividade encontrado com os filtros aplicados.
                            </div>
                            <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-spacing" id="logsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Data/Hora</th>
                                            <th>Usuário</th>
                                            <th>Módulo</th>
                                            <th>Ação</th>
                                            <th>Descrição</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($logs as $log): ?>
                                        <tr>
                                            <td><?= date('d/m/Y H:i:s', strtotime($log->created_at)) ?></td>
                                            <td>
                                                <?php if ($log->user_id): ?>
                                                    <?= isset($users[$log->user_id]) ? $users[$log->user_id] : 'Usuário #'.$log->user_id ?>
                                                <?php else: ?>
                                                    Sistema
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php 
                                                // Exibir badge com o nome do módulo
                                                $badge_class = 'bg-secondary';
                                                switch($log->module) {
                                                    case 'prestadores': $badge_class = 'bg-primary'; break;
                                                    case 'tomadores': $badge_class = 'bg-success'; break;
                                                    case 'inquilinos': $badge_class = 'bg-info'; break;
                                                    case 'imoveis': $badge_class = 'bg-warning text-dark'; break;
                                                    case 'notas': $badge_class = 'bg-danger'; break;
                                                    case 'users': $badge_class = 'bg-dark'; break;
                                                    case 'auth': $badge_class = 'bg-secondary'; break;
                                                    case 'system': $badge_class = 'bg-light text-dark'; break;
                                                }
                                                
                                                echo '<span class="badge ' . $badge_class . '">';
                                                
                                                // Nome amigável do módulo
                                                switch($log->module) {
                                                    case 'prestadores': echo 'Prestadores'; break;
                                                    case 'tomadores': echo 'Tomadores'; break;
                                                    case 'inquilinos': echo 'Inquilinos'; break;
                                                    case 'imoveis': echo 'Imóveis'; break;
                                                    case 'notas': echo 'Notas Fiscais'; break;
                                                    case 'users': echo 'Usuários'; break;
                                                    case 'auth': echo 'Autenticação'; break;
                                                    case 'system': echo 'Sistema'; break;
                                                    default: echo $log->module ?? 'N/A';
                                                }
                                                
                                                echo '</span>';
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                // Exibir badge com o nome da ação
                                                $badge_class = 'bg-secondary';
                                                switch($log->action) {
                                                    case 'create': $badge_class = 'bg-success'; break;
                                                    case 'update': $badge_class = 'bg-primary'; break;
                                                    case 'delete': $badge_class = 'bg-danger'; break;
                                                    case 'login': $badge_class = 'bg-info'; break;
                                                    case 'logout': $badge_class = 'bg-warning text-dark'; break;
                                                    case 'import': $badge_class = 'bg-info'; break;
                                                    case 'export': $badge_class = 'bg-info'; break;
                                                    case 'view': $badge_class = 'bg-secondary'; break;
                                                    case 'dimob': $badge_class = 'bg-dark'; break;
                                                }
                                                
                                                echo '<span class="badge ' . $badge_class . '">';
                                                
                                                // Nome amigável da ação
                                                switch($log->action) {
                                                    case 'create': echo 'Criar'; break;
                                                    case 'update': echo 'Atualizar'; break;
                                                    case 'delete': echo 'Excluir'; break;
                                                    case 'login': echo 'Login'; break;
                                                    case 'logout': echo 'Logout'; break;
                                                    case 'import': echo 'Importar'; break;
                                                    case 'export': echo 'Exportar'; break;
                                                    case 'view': echo 'Visualizar'; break;
                                                    case 'dimob': echo 'DIMOB'; break;
                                                    default: echo $log->action;
                                                }
                                                
                                                echo '</span>';
                                                
                                                // Se a ação for importação e tiver status no data_after, mostrar um badge adicional
                                                $data_after = !empty($log->data_after) ? json_decode($log->data_after, true) : null;
                                                if ($log->action == 'import' && isset($data_after['status'])) {
                                                    $status_badge = 'bg-secondary';
                                                    switch($data_after['status']) {
                                                        case 'inicio': $status_badge = 'bg-warning text-dark'; break;
                                                        case 'finalizado': $status_badge = 'bg-success'; break;
                                                    }
                                                    echo ' <span class="badge ' . $status_badge . '">' . ucfirst($data_after['status']) . '</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="text-truncate" style="max-width: 300px;" title="<?= htmlspecialchars($log->description) ?>">
                                                <?= $log->description ?>
                                                <?php 
                                                // Se for um log de importação com batch_id, mostrar o ID do lote
                                                $data_after = !empty($log->data_after) ? json_decode($log->data_after, true) : null;
                                                if ($log->action == 'import' && !empty($data_after['batch_id'])) {
                                                    echo ' <span class="badge bg-secondary">Batch: ' . $data_after['batch_id'] . '</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= base_url('logs/view/' . $log->id) ?>" class="btn btn-info" title="Visualizar">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php 
                                                    // Se for log de importação com batch_id, adiciona link para ver todas as notas
                                                    $data_after = !empty($log->data_after) ? json_decode($log->data_after, true) : null;
                                                    if ($log->action == 'import' && !empty($data_after['batch_id'])) {
                                                    ?>
                                                    <a href="<?= base_url('notas/listar_por_batch/' . $data_after['batch_id']) ?>" class="btn btn-primary" title="Ver Notas">
                                                        <i class="fas fa-list"></i>
                                                    </a>
                                                    <?php } ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Paginação -->
                            <div class="d-flex justify-content-center mt-4">
                                <?= $this->pagination->create_links() ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Limpar Logs Antigos -->
<div class="modal fade" id="cleanLogsModal" tabindex="-1" aria-labelledby="cleanLogsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('logs/clean') ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="cleanLogsModalLabel">Limpar Logs Antigos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Atenção!</strong> Esta operação irá remover permanentemente os logs antigos do sistema. Esta ação não pode ser desfeita.
                    </div>
                    
                    <div class="mb-3">
                        <label for="days" class="form-label">Remover logs com mais de quantos dias?</label>
                        <input type="number" name="days" id="days" class="form-control" min="30" value="90" required>
                        <div class="form-text">Por segurança, o sistema exige que você mantenha pelo menos 30 dias de logs.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Limpar Logs Antigos</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Adicionar links para DataTables e CSS
$(document).ready(function() {
    // Inicializar Select2 para os filtros
    if ($.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
    }
    
    // Ajustar datas iniciais se não estiverem definidas
    if ($('#date_start').val() === '') {
        // Por padrão, buscar os últimos 30 dias
        var thirtyDaysAgo = new Date();
        thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
        $('#date_start').val(thirtyDaysAgo.toISOString().split('T')[0]);
    }
    
    if ($('#date_end').val() === '') {
        // Data final padrão é hoje
        var today = new Date();
        $('#date_end').val(today.toISOString().split('T')[0]);
    }
});
</script>
