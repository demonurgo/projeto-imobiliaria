<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Logs de Importação de Notas Fiscais</h1>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtrar Logs</h6>
        </div>
        <div class="card-body">
            <form action="<?= site_url('logs/importacao'); ?>" method="get" class="form-inline">
                <div class="form-group mx-sm-3 mb-2">
                    <label for="batch_id" class="sr-only">Batch ID</label>
                    <input type="text" class="form-control" id="batch_id" name="batch_id" placeholder="Batch ID" value="<?= isset($filtros['batch_id']) ? $filtros['batch_id'] : ''; ?>">
                </div>
                <div class="form-group mx-sm-3 mb-2">
                    <label for="data_inicio" class="sr-only">Data Início</label>
                    <input type="date" class="form-control" id="data_inicio" name="data_inicio" placeholder="Data Início" value="<?= isset($filtros['data_inicio']) ? $filtros['data_inicio'] : ''; ?>">
                </div>
                <div class="form-group mx-sm-3 mb-2">
                    <label for="data_fim" class="sr-only">Data Fim</label>
                    <input type="date" class="form-control" id="data_fim" name="data_fim" placeholder="Data Fim" value="<?= isset($filtros['data_fim']) ? $filtros['data_fim'] : ''; ?>">
                </div>
                <button type="submit" class="btn btn-primary mb-2">Filtrar</button>
                <a href="<?= site_url('logs/importacao'); ?>" class="btn btn-secondary mb-2 ml-2">Limpar Filtros</a>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Logs de Importação</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Data/Hora</th>
                            <th>Usuário</th>
                            <th>Tipo</th>
                            <th>Batch ID</th>
                            <th>Descrição</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($logs)): ?>
                            <?php foreach ($logs as $log): ?>
                                <?php 
                                    // Extrair batch_id e outros dados adicionais
                                    $dados_adicionais = json_decode($log->dados_adicionais, true); 
                                    $batch_id = isset($dados_adicionais['batch_id']) ? $dados_adicionais['batch_id'] : '';
                                ?>
                                <tr>
                                    <td><?= date('d/m/Y H:i:s', strtotime($log->created_at)); ?></td>
                                    <td>
                                        <?php if ($log->usuario_id): ?>
                                            <?= $usuarios[$log->usuario_id] ?? 'Usuário #'.$log->usuario_id; ?>
                                        <?php else: ?>
                                            Sistema
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $log->tipo; ?></td>
                                    <td><?= $batch_id; ?></td>
                                    <td><?= $log->descricao; ?></td>
                                    <td>
                                        <a href="<?= site_url('logs/detalhes/' . $log->id); ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Detalhes
                                        </a>
                                        <?php if ($batch_id && $log->tipo == 'importacao_notas'): ?>
                                            <a href="<?= site_url('notas/listar_por_batch/' . $batch_id); ?>" class="btn btn-primary btn-sm">
                                                <i class="fas fa-list"></i> Ver Notas
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Nenhum log de importação encontrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if (isset($pagination)): ?>
                <div class="mt-3">
                    <?= $pagination; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
