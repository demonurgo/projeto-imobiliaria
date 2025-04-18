<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-building"></i> Detalhes do Imóvel</h5>
                    <div>
                        <a href="<?= base_url('imoveis/edit/'.$imovel['id']) ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="<?= base_url('imoveis') ?>" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Voltar
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
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="mb-3">
                                <?php if(!empty($imovel['codigo_referencia'])): ?>
                                <span class="badge bg-secondary"><?= $imovel['codigo_referencia'] ?></span>
                                <?php endif; ?>
                                
                                <?= $imovel['endereco'] ?>
                                <?php if(!empty($imovel['numero'])): ?>, <?= $imovel['numero'] ?><?php endif; ?>
                                <?php if(!empty($imovel['complemento'])): ?> - <?= $imovel['complemento'] ?><?php endif; ?>
                            </h2>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0">Dados do Imóvel</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        <strong>Bairro:</strong> <?= $imovel['bairro'] ?? 'Não informado' ?><br>
                                        <strong>Cidade/UF:</strong> 
                                        <?php if(!empty($imovel['cidade']) || !empty($imovel['uf'])): ?>
                                            <?= $imovel['cidade'] ?>/<?= $imovel['uf'] ?>
                                        <?php else: ?>
                                            Não informado
                                        <?php endif; ?><br>
                                        <strong>CEP:</strong> 
                                        <?php if(!empty($imovel['cep'])): ?>
                                            <?= substr($imovel['cep'], 0, 5) . '-' . substr($imovel['cep'], 5) ?>
                                        <?php else: ?>
                                            Não informado
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0">Dados do Contrato</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        <strong>Inquilino:</strong> 
                                        <?php if(!empty($imovel['inquilino_id']) && isset($imovel['inquilino_nome'])): ?>
                                            <a href="<?= base_url('inquilinos/view/'.$imovel['inquilino_id']) ?>">
                                                <?= $imovel['inquilino_nome'] ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">Sem inquilino</span>
                                        <?php endif; ?><br>
                                        
                                        <strong>Valor do Aluguel:</strong> 
                                        <?php if(!empty($imovel['valor_aluguel'])): ?>
                                            R$ <?= number_format($imovel['valor_aluguel'], 2, ',', '.') ?>
                                        <?php else: ?>
                                            <span class="text-muted">Não informado</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (!empty($imovel['observacoes'])): ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0">Observações</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        <?= nl2br($imovel['observacoes']) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Notas Fiscais do Imóvel -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="card-title mb-0"><i class="fas fa-file-invoice"></i> Notas Fiscais Associadas</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($notas)): ?>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> Nenhuma nota fiscal associada a este imóvel.
                                    </div>
                                    <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Número</th>
                                                    <th>Data Emissão</th>
                                                    <th>Tomador</th>
                                                    <th>Inquilino</th>
                                                    <th>Valor de Aluguel</th>
                                                    <th>Valor de Comissão</th>
                                                    <th>Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($notas as $nota): ?>
                                                <tr>
                                                    <td><?= $nota['numero'] ?></td>
                                                    <td><?= date('d/m/Y', strtotime($nota['data_emissao'])) ?></td>
                                                    <td><?= $nota['tomador_nome'] ?></td>
                                                    <td>
                                                        <?php if($nota['inquilino_id'] && isset($nota['inquilino_nome'])): ?>
                                                            <?= $nota['inquilino_nome'] ?>
                                                        <?php else: ?>
                                                            <span class="badge bg-warning text-dark">Não identificado</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>R$ <?= isset($nota['valor_aluguel']) ? number_format($nota['valor_aluguel'], 2, ',', '.') : (isset($imovel['valor_aluguel']) ? number_format($imovel['valor_aluguel'], 2, ',', '.') : '0,00') ?></td>
                                                    <td>R$ <?= number_format($nota['valor_servicos'], 2, ',', '.') ?></td>
                                                    <td>
                                                        <a href="<?= base_url('notas/view/'.$nota['id']) ?>" class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i> Ver
                                                        </a>
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
                    
                    <div class="d-flex justify-content-end">
                        <a href="<?= base_url('imoveis') ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Voltar para Lista
                        </a>
                        <a href="<?= base_url('imoveis/edit/'.$imovel['id']) ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Editar Imóvel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
