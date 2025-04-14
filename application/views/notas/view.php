<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-file-invoice"></i> Detalhes da Nota Fiscal #<?= $nota['numero'] ?></h5>
                    <div>
                        <a href="<?= base_url('notas/edit/'.$nota['id']) ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="<?= base_url('notas') ?>" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                
                    <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?>
                    </div>
                    <?php endif; ?>
                    
                    <h4 class="border-bottom pb-2">Informações Gerais</h4>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <p><strong>Número:</strong> <?= $nota['numero'] ?></p>
                            <p><strong>Código de Verificação:</strong> <?= $nota['codigo_verificacao'] ?></p>
                            <p><strong>Status:</strong> 
                                <?php 
                                switch($nota['status']) {
                                    case 'importado':
                                        echo '<span class="badge bg-info">Importado</span>';
                                        break;
                                    case 'processado':
                                        echo '<span class="badge bg-success">Processado</span>';
                                        break;
                                    case 'atualizado':
                                        echo '<span class="badge bg-primary">Atualizado</span>';
                                        break;
                                    case 'cancelado':
                                        echo '<span class="badge bg-danger">Cancelado</span>';
                                        break;
                                    default:
                                        echo '<span class="badge bg-secondary">Desconhecido</span>';
                                }
                                ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Data de Emissão:</strong> <?= date('d/m/Y H:i', strtotime($nota['data_emissao'])) ?></p>
                            <p><strong>Competência:</strong> <?= $nota['competencia'] ? date('m/Y', strtotime($nota['competencia'])) : 'Não informada' ?></p>
                            <p><strong>DIMOB:</strong> 
                                <?php if($nota['dimob_enviado']): ?>
                                <span class="badge bg-success">Incluído</span>
                                <?php else: ?>
                                <span class="badge bg-secondary">Pendente</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Valor dos Serviços:</strong> R$ <?= number_format($nota['valor_servicos'], 2, ',', '.') ?></p>
                            <p><strong>Valor do ISS:</strong> R$ <?= number_format($nota['valor_iss'], 2, ',', '.') ?></p>
                            <p><strong>Alíquota:</strong> <?= number_format($nota['aliquota'] * 100, 2, ',', '.') ?>%</p>
                        </div>
                    </div>
                    
                    <h4 class="border-bottom pb-2">Prestador e Tomador</h4>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Prestador de Serviço</h5>
                            <p><strong>Nome:</strong> <?= $nota['prestador_nome'] ?></p>
                            <?php 
                            // Assumindo que temos dados completos do prestador
                            // Na implementação real, você precisaria carregar esses dados
                            ?>
                        </div>
                        <div class="col-md-6">
                            <h5>Tomador de Serviço</h5>
                            <p><strong>Nome:</strong> <?= $nota['tomador_nome'] ?></p>
                            <?php 
                            // Assumindo que temos dados completos do tomador
                            // Na implementação real, você precisaria carregar esses dados
                            ?>
                        </div>
                    </div>
                    
                    <h4 class="border-bottom pb-2">Informações DIMOB</h4>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Inquilino</h5>
                            <?php if($nota['inquilino_id'] && isset($nota['inquilino_nome'])): ?>
                                <p><strong>Nome:</strong> <?= $nota['inquilino_nome'] ?></p>
                                <?php 
                                // Assumindo que temos dados completos do inquilino
                                // Na implementação real, você precisaria carregar esses dados
                                ?>
                            <?php else: ?>
                                <p><span class="badge bg-warning text-dark">Inquilino não identificado</span></p>
                                <p>É necessário editar esta nota e informar o inquilino para inclusão na DIMOB.</p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Imóvel</h5>
                            <?php if(isset($nota['imovel_id']) && $nota['imovel_id']): ?>
                                <?php 
                                // Aqui você precisaria carregar os dados do imóvel
                                // Na implementação atual, vamos apenas mostrar uma mensagem genérica
                                ?>
                                <p><strong>Imóvel associado a esta nota.</strong></p>
                            <?php else: ?>
                                <p><span class="badge bg-warning text-dark">Imóvel não associado</span></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <h4 class="border-bottom pb-2">Discriminação do Serviço</h4>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p><strong>Descrição:</strong> <?= $nota['descricao_servico'] ?></p>
                                    <p><strong>Discriminação Original:</strong></p>
                                    <pre class="bg-white p-3 border rounded"><?= $nota['discriminacao'] ?></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="<?= base_url('notas') ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Voltar para Lista
                        </a>
                        <a href="<?= base_url('notas/edit/'.$nota['id']) ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Editar Nota
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
