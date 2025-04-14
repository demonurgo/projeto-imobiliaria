<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-building"></i> Detalhes do Prestador</h5>
                    <div>
                        <a href="<?= base_url('prestadores/edit/'.$prestador['id']) ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="<?= base_url('prestadores') ?>" class="btn btn-light btn-sm">
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
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0">Informações do Prestador</h6>
                                </div>
                                <div class="card-body">
                                    <h3 class="mb-3"><?= $prestador['razao_social'] ?></h3>
                                    
                                    <p>
                                        <strong>CNPJ:</strong>
                                        <?php 
                                        $cnpj = $prestador['cnpj'];
                                        if(strlen($cnpj) === 14) {
                                            echo substr($cnpj, 0, 2).'.'.substr($cnpj, 2, 3).'.'.substr($cnpj, 5, 3).'/'.substr($cnpj, 8, 4).'-'.substr($cnpj, 12, 2);
                                        } else {
                                            echo $cnpj;
                                        }
                                        ?>
                                    </p>
                                    
                                    <p><strong>Inscrição Municipal:</strong> <?= $prestador['inscricao_municipal'] ?? 'Não informado' ?></p>
                                    <p><strong>Email:</strong> <?= $prestador['email'] ?? 'Não informado' ?></p>
                                    <p><strong>Telefone:</strong> <?= $prestador['telefone'] ?? 'Não informado' ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0">Endereço</h6>
                                </div>
                                <div class="card-body">
                                    <p>
                                        <?= $prestador['endereco'] ?? '' ?>
                                        <?= !empty($prestador['numero']) ? ', '.$prestador['numero'] : '' ?>
                                        <?= !empty($prestador['complemento']) ? ' - '.$prestador['complemento'] : '' ?>
                                        <br>
                                        <?= !empty($prestador['bairro']) ? $prestador['bairro'] : '' ?>
                                        <br>
                                        <?php 
                                        $cidade = '';
                                        if(!empty($prestador['codigo_municipio'])) {
                                            // Aqui você poderia ter uma função para converter o código em nome da cidade
                                            $cidade = 'Código IBGE: ' . $prestador['codigo_municipio'];
                                        }
                                        echo $cidade;
                                        ?>
                                        <?= !empty($prestador['uf']) ? ' - '.$prestador['uf'] : '' ?>
                                        <br>
                                        <?php 
                                        if(!empty($prestador['cep'])) {
                                            $cep = $prestador['cep'];
                                            if(strlen($cep) === 8) {
                                                echo 'CEP: ' . substr($cep, 0, 5).'-'.substr($cep, 5, 3);
                                            } else {
                                                echo 'CEP: ' . $cep;
                                            }
                                        }
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notas Fiscais do Prestador -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="card-title mb-0"><i class="fas fa-file-invoice"></i> Notas Fiscais Emitidas</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($notas)): ?>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> Nenhuma nota fiscal emitida por este prestador.
                                    </div>
                                    <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="notasTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Número</th>
                                                    <th>Data Emissão</th>
                                                    <th>Tomador</th>
                                                    <th>Valor</th>
                                                    <th>Status</th>
                                                    <th>Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($notas as $nota): ?>
                                                <tr>
                                                    <td><?= $nota['numero'] ?></td>
                                                    <td><?= date('d/m/Y', strtotime($nota['data_emissao'])) ?></td>
                                                    <td><?= $nota['tomador_nome'] ?? 'Não identificado' ?></td>
                                                    <td>R$ <?= number_format($nota['valor_servicos'], 2, ',', '.') ?></td>
                                                    <td>
                                                        <?php
                                                        $status_class = '';
                                                        switch($nota['status']) {
                                                            case 'importado': $status_class = 'bg-info'; break;
                                                            case 'processado': $status_class = 'bg-success'; break;
                                                            case 'atualizado': $status_class = 'bg-warning'; break;
                                                            case 'cancelado': $status_class = 'bg-danger'; break;
                                                            default: $status_class = 'bg-secondary';
                                                        }
                                                        ?>
                                                        <span class="badge <?= $status_class ?>">
                                                            <?= ucfirst($nota['status']) ?>
                                                        </span>
                                                    </td>
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
                    
                    <div class="mt-4 d-flex justify-content-end">
                        <a href="<?= base_url('prestadores') ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Voltar para Lista
                        </a>
                        <a href="<?= base_url('prestadores/edit/'.$prestador['id']) ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Editar Prestador
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#notasTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
        },
        "order": [[1, "desc"]], // Ordenar por data de emissão (decrescente)
        "pageLength": 10,
        "responsive": true
    });
});
</script>