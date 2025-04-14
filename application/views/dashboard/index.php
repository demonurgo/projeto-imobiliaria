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
        <div class="col-md-4 mb-4">
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
        
        <div class="col-md-4 mb-4">
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
        
        <div class="col-md-4 mb-4">
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
                    <h5 class="card-title mb-0"><i class="fas fa-clipboard-list"></i> Últimas Atividades</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($notas_recentes)): ?>
                        <div class="alert alert-secondary">
                            <i class="fas fa-info-circle"></i> Nenhuma atividade recente para exibir.
                        </div>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($notas_recentes as $nota): ?>
                                <li class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Nota #<?= $nota['numero'] ?></h6>
                                        <small><?= date('d/m/Y', strtotime($nota['data_emissao'])) ?></small>
                                    </div>
                                    <p class="mb-1">Valor: R$ <?= number_format($nota['valor_servicos'], 2, ',', '.') ?></p>
                                    <small>Status: <?= ucfirst($nota['status']) ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
