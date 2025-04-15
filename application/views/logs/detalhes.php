<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detalhes do Log de Importação</h1>
        <a href="<?= site_url('logs/importacao'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar para Logs
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informações Gerais</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 25%;">ID do Log</th>
                    <td><?= $log->id; ?></td>
                </tr>
                <tr>
                    <th>Data/Hora</th>
                    <td><?= date('d/m/Y H:i:s', strtotime($log->created_at)); ?></td>
                </tr>
                <tr>
                    <th>Tipo</th>
                    <td><?= $log->tipo; ?></td>
                </tr>
                <tr>
                    <th>Descrição</th>
                    <td><?= $log->descricao; ?></td>
                </tr>
                <tr>
                    <th>Usuário</th>
                    <td>
                        <?php if ($log->usuario_id): ?>
                            <?= $usuario['nome'] ?? 'Usuário #'.$log->usuario_id; ?> 
                            <?= isset($usuario['email']) ? '('.$usuario['email'].')' : ''; ?>
                        <?php else: ?>
                            Sistema
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>IP</th>
                    <td><?= $log->ip; ?></td>
                </tr>
            </table>
        </div>
    </div>

    <?php if (!empty($dados_adicionais)): ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Dados da Importação</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <?php if (isset($dados_adicionais['batch_id'])): ?>
                <tr>
                    <th style="width: 25%;">Batch ID</th>
                    <td><?= $dados_adicionais['batch_id']; ?></td>
                </tr>
                <?php endif; ?>
                
                <?php if (isset($dados_adicionais['arquivo'])): ?>
                <tr>
                    <th>Arquivo</th>
                    <td><?= $dados_adicionais['arquivo']; ?></td>
                </tr>
                <?php endif; ?>
                
                <?php if (isset($dados_adicionais['resumo'])): ?>
                <tr>
                    <th>Resumo da Importação</th>
                    <td>
                        <table class="table table-sm">
                            <?php foreach ($dados_adicionais['resumo'] as $chave => $valor): ?>
                                <?php if ($chave != 'erros'): ?>
                                <tr>
                                    <th><?= ucfirst(str_replace('_', ' ', $chave)); ?></th>
                                    <td><?= $valor; ?></td>
                                </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </table>
                        
                        <?php if (isset($dados_adicionais['resumo']['erros']) && !empty($dados_adicionais['resumo']['erros'])): ?>
                        <div class="mt-3">
                            <h6 class="font-weight-bold">Erros Encontrados:</h6>
                            <ul class="list-group">
                                <?php foreach ($dados_adicionais['resumo']['erros'] as $erro): ?>
                                <li class="list-group-item list-group-item-danger"><?= $erro; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endif; ?>
                
                <?php if (isset($dados_adicionais['notas_processadas'])): ?>
                <tr>
                    <th>Notas Processadas</th>
                    <td><?= $dados_adicionais['notas_processadas']; ?></td>
                </tr>
                <?php endif; ?>
                
                <?php if ($log->tipo == 'nota_importada' && isset($dados_adicionais['prestador'])): ?>
                <tr>
                    <th>Prestador</th>
                    <td><?= $dados_adicionais['prestador']; ?></td>
                </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($log->tipo == 'importacao_notas' && isset($dados_adicionais['batch_id'])): ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Notas Importadas neste Lote</h6>
            <a href="<?= site_url('notas/listar_por_batch/' . $dados_adicionais['batch_id']); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-list"></i> Ver Todas as Notas
            </a>
        </div>
        <div class="card-body">
            <?php if (!empty($notas_do_lote)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Número</th>
                                <th>Data Emissão</th>
                                <th>Valor</th>
                                <th>Tomador</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($notas_do_lote as $nota): ?>
                                <tr>
                                    <td><?= $nota['numero']; ?></td>
                                    <td><?= date('d/m/Y', strtotime($nota['data_emissao'])); ?></td>
                                    <td>R$ <?= number_format($nota['valor_servicos'], 2, ',', '.'); ?></td>
                                    <td><?= $nota['tomador_nome']; ?></td>
                                    <td>
                                        <?php if ($nota['status'] == 'processado'): ?>
                                            <span class="badge badge-success">Processado</span>
                                        <?php elseif ($nota['status'] == 'revisar'): ?>
                                            <span class="badge badge-warning">Revisar</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary"><?= ucfirst($nota['status']); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= site_url('notas/visualizar/' . $nota['id']); ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (count($notas_do_lote) > 10): ?>
                    <div class="mt-3 text-center">
                        <a href="<?= site_url('notas/listar_por_batch/' . $dados_adicionais['batch_id']); ?>" class="btn btn-primary">
                            Ver Todas as Notas do Lote
                        </a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-info">Nenhuma nota encontrada para este lote.</div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
