<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.16/dist/jquery.mask.min.js"></script>

<div class="container-fluid">

    <!-- Cabeçalho da Página -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-search"></i> Detalhes do Log #<?= $log->id ?>
        </h1>
        <a href="<?= base_url('logs') ?>" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar para Lista
        </a>
    </div>

    <!-- Card de Informações Básicas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Informações Básicas</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">ID do Log</dt>
                        <dd class="col-sm-8"><?= $log->id ?></dd>
                        
                        <dt class="col-sm-4">Data/Hora</dt>
                        <dd class="col-sm-8"><?= date('d/m/Y H:i:s', strtotime($log->created_at)) ?></dd>
                        
                        <dt class="col-sm-4">Usuário</dt>
                        <dd class="col-sm-8">
													<?php 
							$usuario = (array) $usuario; // Convert object to array
							if ($log->user_id): ?>
								<?= isset($usuario['nome']) ? $usuario['nome'] : 'Usuário #' . $log->user_id ?>
							<?php else: ?>
                                <span class="badge bg-info">Sistema</span>
                            <?php endif; ?>
                        </dd>
                        
                        <dt class="col-sm-4">IP</dt>
                        <dd class="col-sm-8"><?= $log->ip_address ?></dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">Módulo</dt>
                        <dd class="col-sm-8">
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
                                default: echo $log->module ?? 'Não definido';
                            }
                            
                            echo '</span>';
                            ?>
                        </dd>
                        
                        <dt class="col-sm-4">Ação</dt>
                        <dd class="col-sm-8">
                            <?php 
                            // Exibir badge com o tipo
                            $badge_class = 'bg-secondary';
                            switch($log->action) {
                                case 'create': $badge_class = 'bg-success'; break;
                                case 'update': $badge_class = 'bg-primary'; break;
                                case 'delete': $badge_class = 'bg-danger'; break;
                                case 'import': $badge_class = 'bg-info'; break;
                                case 'export': $badge_class = 'bg-warning text-dark'; break;
                                case 'view': $badge_class = 'bg-secondary'; break;
                                case 'login': $badge_class = 'bg-dark'; break;
                                case 'logout': $badge_class = 'bg-dark'; break;
                            }
                            
                            echo '<span class="badge ' . $badge_class . '">';
                            echo htmlspecialchars($log->action);
                            echo '</span>';
                            
                            // Se a ação for importação e tiver status, mostrar um segundo badge
                            $data_after = json_decode($log->data_after, true);
                            if ($log->action == 'import' && isset($data_after['status'])) {
                                $status_badge = 'bg-secondary';
                                switch($data_after['status']) {
                                    case 'inicio': $status_badge = 'bg-warning text-dark'; break;
                                    case 'finalizado': $status_badge = 'bg-success'; break;
                                }
                                echo ' <span class="badge ' . $status_badge . '">' . ucfirst($data_after['status']) . '</span>';
                            }
                            
                            // Se for importação de nota individual
                            if ($log->action == 'import' && isset($data_after['tipo']) && $data_after['tipo'] == 'nota_individual') {
                                echo ' <span class="badge bg-primary">Nota Individual</span>';
                            }
                            ?>
                        </dd>
                        
                        <dt class="col-sm-4">ID do Registro</dt>
                        <dd class="col-sm-8">
                            <?php if ($log->record_id): ?>
                                <?= $log->record_id ?>
                            <?php else: ?>
                                <span class="text-muted">Não aplicável</span>
                            <?php endif; ?>
                        </dd>
                        
                        <?php if ($log->action == 'import'): 
                            $data_after = json_decode($log->data_after, true);
                            if (isset($data_after['batch_id'])): ?>
                        <dt class="col-sm-4">Batch ID</dt>
                        <dd class="col-sm-8">
                            <?= $data_after['batch_id'] ?>
                        </dd>
                        <?php endif; endif; ?>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Descrição do Log -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Descrição da Atividade</h6>
        </div>
        <div class="card-body">
            <div class="alert alert-light">
                <?= nl2br(htmlspecialchars($log->description)) ?>
            </div>
        </div>
    </div>
    
    <?php 
    // Verificar se temos data_after ou data_before
    $data_after = !empty($log->data_after) ? json_decode($log->data_after, true) : null;
    $data_before = !empty($log->data_before) ? json_decode($log->data_before, true) : null;
    
    if (!empty($data_after) || !empty($data_before)): 
    ?>
    <!-- Dados Adicionais -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Dados Adicionais</h6>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="dataTabs" role="tablist">
                <?php if (!empty($data_before)): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="before-tab" data-bs-toggle="tab" data-bs-target="#before" type="button" role="tab" aria-controls="before" aria-selected="true">Dados Anteriores</button>
                </li>
                <?php endif; ?>
                
                <?php if (!empty($data_after)): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= empty($data_before) ? 'active' : '' ?>" id="after-tab" data-bs-toggle="tab" data-bs-target="#after" type="button" role="tab" aria-controls="after" aria-selected="<?= empty($data_before) ? 'true' : 'false' ?>">Dados Posteriores</button>
                </li>
                <?php endif; ?>
            </ul>
            
            <div class="tab-content p-3" id="dataTabsContent">
                <?php if (!empty($data_before)): ?>
                <div class="tab-pane fade show active" id="before" role="tabpanel" aria-labelledby="before-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Campo</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data_before as $campo => $valor): ?>
                                <tr>
                                    <td width="30%"><strong><?= ucfirst(str_replace('_', ' ', $campo)) ?></strong></td>
                                    <td>
                                        <?php 
                                        if (is_array($valor) || is_object($valor)) {
                                            echo '<pre>' . json_encode($valor, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
                                        } elseif (is_bool($valor)) {
                                            echo $valor ? 'Sim' : 'Não';
                                        } elseif ($valor === null) {
                                            echo '<em>Não definido</em>';
                                        } else {
                                            echo htmlspecialchars($valor);
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($data_after)): ?>
                <div class="tab-pane fade <?= empty($data_before) ? 'show active' : '' ?>" id="after" role="tabpanel" aria-labelledby="after-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Campo</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data_after as $campo => $valor): 
                                    // Pular o campo "erros" para tratá-lo separadamente
                                    if ($campo === 'erros' || $campo === 'resumo') continue;
                                ?>
                                <tr>
                                    <td width="30%"><strong><?= ucfirst(str_replace('_', ' ', $campo)) ?></strong></td>
                                    <td>
                                        <?php 
                                        if (is_array($valor) || is_object($valor)) {
                                            echo '<pre>' . json_encode($valor, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
                                        } elseif (is_bool($valor)) {
                                            echo $valor ? 'Sim' : 'Não';
                                        } elseif ($valor === null) {
                                            echo '<em>Não definido</em>';
                                        } else {
                                            echo htmlspecialchars($valor);
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                
                                <?php if (isset($data_after['resumo']) && is_array($data_after['resumo'])): ?>
                                <tr>
                                    <td><strong>Resumo</strong></td>
                                    <td>
                                        <table class="table table-sm">
                                            <tbody>
                                                <?php foreach ($data_after['resumo'] as $chave => $valor): 
                                                    // Pular o campo "erros" para tratá-lo separadamente
                                                    if ($chave === 'erros') continue;
                                                ?>
                                                <tr>
                                                    <td width="40%"><strong><?= ucfirst(str_replace('_', ' ', $chave)) ?></strong></td>
                                                    <td><?= $valor ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if (isset($data_after['resumo']['erros']) && !empty($data_after['resumo']['erros'])): ?>
                    <div class="mt-4">
                        <h5 class="font-weight-bold">Erros Encontrados</h5>
                        <div class="list-group">
                            <?php foreach ($data_after['resumo']['erros'] as $erro): ?>
                            <div class="list-group-item list-group-item-danger"><?= htmlspecialchars($erro) ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($data_after['erros']) && !empty($data_after['erros'])): ?>
                    <div class="mt-4">
                        <h5 class="font-weight-bold">Erros Encontrados</h5>
                        <div class="list-group">
                            <?php foreach ($data_after['erros'] as $erro): ?>
                            <div class="list-group-item list-group-item-danger"><?= htmlspecialchars($erro) ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if (isset($notas_do_lote) && !empty($notas_do_lote)): 
        // Obter batch_id do data_after se existir
        $batch_id = null;
        if (!empty($log->data_after)) {
            $data_after = json_decode($log->data_after, true);
            if (isset($data_after['batch_id'])) {
                $batch_id = $data_after['batch_id'];
            }
        }
    ?>
    <!-- Notas Relacionadas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Notas Importadas neste Lote</h6>
            <?php if ($batch_id): ?>
            <a href="<?= site_url('notas/listar_por_batch/' . $batch_id) ?>" class="btn btn-sm btn-primary">
                <i class="fas fa-list"></i> Ver Todas as Notas
            </a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
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
                            <td><?= $nota['numero'] ?></td>
                            <td><?= date('d/m/Y', strtotime($nota['data_emissao'])) ?></td>
                            <td class="text-end">R$ <?= number_format($nota['valor_servicos'], 2, ',', '.') ?></td>
                            <td><?= $nota['tomador_nome'] ?></td>
                            <td>
                                <?php
                                $status_class = 'secondary';
                                switch ($nota['status']) {
                                    case 'processado': $status_class = 'success'; break;
                                    case 'revisar': $status_class = 'warning'; break;
                                    case 'importado': $status_class = 'info'; break;
                                    case 'atualizado': $status_class = 'primary'; break;
                                }
                                ?>
                                <span class="badge bg-<?= $status_class ?>"><?= ucfirst($nota['status']) ?></span>
                            </td>
                            <td>
                                <a href="<?= site_url('notas/visualizar/' . $nota['id']) ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if (count($notas_do_lote) >= 10 && $batch_id): ?>
            <div class="mt-3 text-center">
                <a href="<?= site_url('notas/listar_por_batch/' . $batch_id) ?>" class="btn btn-primary">
                    Ver Todas as Notas do Lote
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Botões de Ação -->
    <div class="d-flex justify-content-between mb-4">
        <a href="<?= base_url('logs') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar para Lista
        </a>
        
        <?php if ($log->record_id && $log->module && $log->module != 'system'): ?>
            <?php
            // Determinar URL para visualizar o registro
            $view_url = '';
            switch ($log->module) {
                case 'prestadores':
                    $view_url = base_url('prestadores/visualizar/' . $log->record_id);
                    break;
                case 'tomadores':
                    $view_url = base_url('tomadores/visualizar/' . $log->record_id);
                    break;
                case 'inquilinos':
                    $view_url = base_url('inquilinos/visualizar/' . $log->record_id);
                    break;
                case 'imoveis':
                    $view_url = base_url('imoveis/visualizar/' . $log->record_id);
                    break;
                case 'notas':
                    $view_url = base_url('notas/visualizar/' . $log->record_id);
                    break;
                case 'users':
                    $view_url = base_url('users/visualizar/' . $log->record_id);
                    break;
            }
            ?>
            
            <?php if ($view_url): ?>
            <a href="<?= $view_url ?>" class="btn btn-primary">
                <i class="fas fa-eye"></i> Ver Registro Relacionado
            </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
