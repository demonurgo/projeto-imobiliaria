<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Notas do Lote #<?= $batch_id ?></h1>
        <a href="<?= site_url('logs/importacao'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar para Logs
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Notas Fiscais neste Lote</h6>
        </div>
        <div class="card-body">
            <?php if (!empty($notas)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Número</th>
                                <th>Data Emissão</th>
                                <th>Prestador</th>
                                <th>Tomador</th>
                                <th>Valor (R$)</th>
                                <th>Inquilino</th>
                                <th>DIMOB</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($notas as $nota): ?>
                                <tr>
                                    <td><?= $nota['numero']; ?></td>
                                    <td><?= date('d/m/Y', strtotime($nota['data_emissao'])); ?></td>
                                    <td><?= $nota['prestador_nome']; ?></td>
                                    <td><?= $nota['tomador_nome']; ?></td>
                                    <td class="text-right">R$ <?= number_format($nota['valor_servicos'], 2, ',', '.'); ?></td>
                                    <td>
                                        <?php if (!empty($nota['inquilino_nome'])): ?>
                                            <?= $nota['inquilino_nome']; ?>
                                        <?php else: ?>
                                            <span class="badge badge-warning">Não identificado</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($nota['dimob_enviado']): ?>
                                            <span class="badge badge-success">Incluído</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Pendente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $badge_class = 'secondary';
                                            switch ($nota['status']) {
                                                case 'processado':
                                                    $badge_class = 'success';
                                                    break;
                                                case 'importado':
                                                    $badge_class = 'info';
                                                    break;
                                                case 'revisar':
                                                    $badge_class = 'warning';
                                                    break;
                                                case 'cancelado':
                                                    $badge_class = 'danger';
                                                    break;
                                            }
                                        ?>
                                        <span class="badge badge-<?= $badge_class; ?>"><?= ucfirst($nota['status']); ?></span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="<?= site_url('notas/view/' . $nota['id']); ?>" class="btn btn-info btn-sm" title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= site_url('notas/edit/' . $nota['id']); ?>" class="btn btn-primary btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if (!$nota['dimob_enviado']): ?>
                                                <a href="<?= site_url('notas/dimob/' . $nota['id'] . '/1'); ?>" class="btn btn-success btn-sm" title="Marcar para DIMOB">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= site_url('notas/dimob/' . $nota['id'] . '/0'); ?>" class="btn btn-warning btn-sm" title="Remover da DIMOB">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?= site_url('notas/delete/' . $nota['id']); ?>" class="btn btn-danger btn-sm" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir esta nota fiscal?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">Nenhuma nota encontrada para este lote.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
        },
        "order": [[1, "desc"]]
    });
});
</script>