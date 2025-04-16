<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-tachometer-alt"></i> Dashboard</h5>
                </div>
                <div class="card-body">
                    <h3>Bem-vindo, <?php echo (isset($user) && is_object($user)) ? $user->name : $this->session->userdata('name'); ?>!</h3>
                    <p>Este é o sistema de gerenciamento de NFSe e geração de DIMOB.</p>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Selecione uma opção no menu acima para começar.
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-file-invoice"></i> Notas Fiscais</h5>
                </div>
                <div class="card-body text-center">
                    <h1 class="display-4"><?= isset($total_notas) ? $total_notas : 0 ?></h1>
                    <p class="card-text">Total de Notas Importadas</p>
                    <a href="<?php echo base_url('notas'); ?>" class="btn btn-outline-success">
                        <i class="fas fa-eye"></i> Visualizar
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0"><i class="fas fa-user-tie"></i> Inquilinos</h5>
                </div>
                <div class="card-body text-center">
                    <h1 class="display-4"><?= isset($total_inquilinos) ? $total_inquilinos : 0 ?></h1>
                    <p class="card-text">Total de Inquilinos Cadastrados</p>
                    <a href="<?php echo base_url('inquilinos'); ?>" class="btn btn-outline-warning">
                        <i class="fas fa-eye"></i> Visualizar
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-building"></i> Imóveis</h5>
                </div>
                <div class="card-body text-center">
                    <h1 class="display-4"><?= isset($total_imoveis) ? $total_imoveis : 0 ?></h1>
                    <p class="card-text">Total de Imóveis Cadastrados</p>
                    <a href="<?php echo base_url('imoveis'); ?>" class="btn btn-outline-info">
                        <i class="fas fa-eye"></i> Visualizar
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-file-export"></i> DIMOB</h5>
                </div>
                <div class="card-body text-center">
                    <h1 class="display-4"><?= isset($notas_ano_atual) ? $notas_ano_atual : 0 ?></h1>
                    <p class="card-text">Notas para DIMOB <?= $ano_atual ?></p>
                    <a href="<?php echo base_url('dimob'); ?>" class="btn btn-outline-primary">
                        <i class="fas fa-cog"></i> Gerar DIMOB
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Estatísticas DIMOB -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-chart-pie"></i> Status DIMOB</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="progress" style="height: 30px;">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: <?= $total_notas > 0 ? ($total_dimob_enviado / $total_notas) * 100 : 0 ?>%" 
                                     aria-valuenow="<?= $total_dimob_enviado ?>" aria-valuemin="0" aria-valuemax="<?= $total_notas ?>">
                                    <?= $total_dimob_enviado ?> Notas Processadas
                                </div>
                                <div class="progress-bar bg-warning" role="progressbar" 
                                     style="width: <?= $total_notas > 0 ? ($total_dimob_pendente / $total_notas) * 100 : 0 ?>%" 
                                     aria-valuenow="<?= $total_dimob_pendente ?>" aria-valuemin="0" aria-valuemax="<?= $total_notas ?>">
                                    <?= $total_dimob_pendente ?> Pendentes
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">
                                <p class="mb-1"><i class="fas fa-check-circle text-success"></i> <strong><?= $total_dimob_enviado ?></strong> notas incluídas em arquivos DIMOB</p>
                                <p class="mb-1"><i class="fas fa-exclamation-circle text-warning"></i> <strong><?= $total_dimob_pendente ?></strong> notas pendentes de inclusão</p>
                                <a href="<?php echo base_url('dimob'); ?>" class="btn btn-sm btn-primary mt-2">
                                    <i class="fas fa-file-export"></i> Gerar Novo Arquivo DIMOB
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-tasks"></i> Ações Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="<?php echo base_url('importacao'); ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-file-import"></i> Importar XML de NFS-e
                        </a>
                        <a href="<?php echo base_url('dimob'); ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-file-export"></i> Gerar Arquivo DIMOB
                        </a>
                        <a href="<?php echo base_url('inquilinos/create'); ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-user-plus"></i> Cadastrar Novo Inquilino
                        </a>
                        <a href="<?php echo base_url('imoveis/create'); ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus-square"></i> Cadastrar Novo Imóvel
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-history"></i> Últimas Atividades</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($atividades_recentes)): ?>
                        <div class="alert alert-secondary">
                            <i class="fas fa-info-circle"></i> Nenhuma atividade recente para exibir.
                        </div>
                    <?php else: ?>
                        <div id="ultimas-atividades">
                            <?php foreach ($atividades_recentes as $log): ?>
                                <div class="activity-item border-bottom pb-2 mb-2">
                                    <div class="d-flex w-100 justify-content-between">
                                        <small class="text-muted"><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?></small>
                                        <?php
                                        // Badge da ação
                                        $badge_class = 'bg-secondary';
                                        switch($log['action']) {
                                            case 'create': $badge_class = 'bg-success'; break;
                                            case 'update': $badge_class = 'bg-primary'; break;
                                            case 'delete': $badge_class = 'bg-danger'; break;
                                            case 'login': $badge_class = 'bg-info'; break;
                                            case 'logout': $badge_class = 'bg-warning text-dark'; break;
                                            case 'import': $badge_class = 'bg-info'; break;
                                        }
                                        ?>
                                        <span class="badge <?= $badge_class ?>">
                                            <?php
                                            switch($log['action']) {
                                                case 'create': echo 'Criar'; break;
                                                case 'update': echo 'Atualizar'; break;
                                                case 'delete': echo 'Excluir'; break;
                                                case 'login': echo 'Login'; break;
                                                case 'logout': echo 'Logout'; break;
                                                case 'import': echo 'Importar'; break;
                                                default: echo ucfirst($log['action']);
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <p class="mb-1">
                                        <strong><?= $log['user_name'] ?? 'Sistema' ?></strong>
                                        <?php
                                        // Texto da ação
                                        switch($log['action']) {
                                            case 'create':
                                                echo 'criou um novo registro em ';
                                                break;
                                            case 'update':
                                                echo 'atualizou um registro em ';
                                                break;
                                            case 'delete':
                                                echo 'excluiu um registro em ';
                                                break;
                                            case 'login':
                                                echo 'realizou login no sistema';
                                                break;
                                            case 'logout':
                                                echo 'saiu do sistema';
                                                break;
                                            case 'import':
                                                echo 'importou dados para ';
                                                break;
                                            default:
                                                echo $log['action'] . ' em ';
                                        }
                                        
                                        // Texto do módulo
                                        if ($log['action'] != 'login' && $log['action'] != 'logout') {
                                            switch($log['module']) {
                                                case 'prestadores':
                                                    echo 'Prestadores';
                                                    break;
                                                case 'tomadores':
                                                    echo 'Tomadores';
                                                    break;
                                                case 'inquilinos':
                                                    echo 'Inquilinos';
                                                    break;
                                                case 'imoveis':
                                                    echo 'Imóveis';
                                                    break;
                                                case 'notas':
                                                    echo 'Notas Fiscais';
                                                    break;
                                                case 'users':
                                                    echo 'Usuários';
                                                    break;
                                                default:
                                                    echo $log['module'];
                                            }
                                        }
                                        ?>
                                    </p>
                                    <?php if (!empty($log['description'])): ?>
                                        <small class="text-muted d-block text-truncate"><?= $log['description'] ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                            
                            <div class="text-center mt-3">
                                <a href="<?= base_url('logs') ?>" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-list"></i> Ver Todos os Logs
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
