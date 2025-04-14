<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-user"></i> Detalhes do Inquilino</h5>
                    <div>
                        <a href="<?= base_url('inquilinos/edit/'.$inquilino['id']) ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="<?= base_url('inquilinos') ?>" class="btn btn-light btn-sm">
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
                            <h2 class="mb-3"><?= $inquilino['nome'] ?></h2>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0">Documento</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        <?php 
                                        $doc = $inquilino['cpf_cnpj'];
                                        if ($tipo_documento == 'cpf') {
                                            echo 'CPF: ' . substr($doc, 0, 3) . '.' . substr($doc, 3, 3) . '.' . substr($doc, 6, 3) . '-' . substr($doc, 9, 2);
                                        } else {
                                            echo 'CNPJ: ' . substr($doc, 0, 2) . '.' . substr($doc, 2, 3) . '.' . substr($doc, 5, 3) . '/' . substr($doc, 8, 4) . '-' . substr($doc, 12, 2);
                                        }
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0">Contato</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        <strong>Telefone:</strong> <?= $inquilino['telefone'] ?? 'Não informado' ?><br>
                                        <strong>Email:</strong> <?= $inquilino['email'] ?? 'Não informado' ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0">Endereço</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        <?= $inquilino['endereco'] ?? 'Não informado' ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (!empty($inquilino['observacoes'])): ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0">Observações</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        <?= nl2br($inquilino['observacoes']) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Imóveis do Inquilino -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="card-title mb-0"><i class="fas fa-building"></i> Imóveis Associados</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($imoveis)): ?>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> Nenhum imóvel associado a este inquilino.
                                    </div>
                                    <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Endereço</th>
                                                    <th>Valor Aluguel</th>
                                                    <th>Código Ref.</th>
                                                    <th>Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($imoveis as $imovel): ?>
                                                <tr>
                                                    <td><?= $imovel['endereco'] ?></td>
                                                    <td>
                                                        <?php if (!empty($imovel['valor_aluguel'])): ?>
                                                            R$ <?= number_format($imovel['valor_aluguel'], 2, ',', '.') ?>
                                                        <?php else: ?>
                                                            Não informado
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= $imovel['codigo_referencia'] ?? 'N/A' ?></td>
                                                    <td>
                                                        <a href="<?= base_url('imoveis/view/'.$imovel['id']) ?>" class="btn btn-info btn-sm">
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
                    
                    <!-- Notas Fiscais do Inquilino -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="card-title mb-0"><i class="fas fa-file-invoice"></i> Notas Fiscais Associadas</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($notas)): ?>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> Nenhuma nota fiscal associada a este inquilino.
                                    </div>
                                    <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Número</th>
                                                    <th>Data Emissão</th>
                                                    <th>Prestador</th>
                                                    <th>Tomador</th>
                                                    <th>Valor</th>
                                                    <th>Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($notas as $nota): ?>
                                                <tr>
                                                    <td><?= $nota['numero'] ?></td>
                                                    <td><?= date('d/m/Y', strtotime($nota['data_emissao'])) ?></td>
                                                    <td><?= $nota['prestador_nome'] ?></td>
                                                    <td><?= $nota['tomador_nome'] ?></td>
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
                        <a href="<?= base_url('inquilinos') ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Voltar para Lista
                        </a>
                        <a href="<?= base_url('inquilinos/edit/'.$inquilino['id']) ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Editar Inquilino
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
