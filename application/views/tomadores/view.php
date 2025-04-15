<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-user-tie"></i> Detalhes do Tomador</h5>
                    <div>
                        <a href="<?= base_url('tomadores/edit/'.$tomador['id']) ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="<?= base_url('tomadores') ?>" class="btn btn-light btn-sm">
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
                                    <h6 class="card-title mb-0">Informações do Tomador</h6>
                                </div>
                                <div class="card-body">
                                    <h3 class="mb-3"><?= $tomador['razao_social'] ?></h3>
                                    
                                    <p>
                                        <strong><?= strlen($tomador['cpf_cnpj']) === 11 ? 'CPF' : 'CNPJ' ?>:</strong>
                                        <?php 
                                        $doc = $tomador['cpf_cnpj'];
                                        if(strlen($doc) === 11) {
                                            echo mask($doc, '###.###.###-##');
                                        } else if(strlen($doc) === 14) {
                                            echo mask($doc, '##.###.###/####-##');
                                        } else {
                                            echo $doc;
                                        }
                                        ?>
                                    </p>
                                    
                                    <p><strong>Email:</strong> <?= $tomador['email'] ?? 'Não informado' ?></p>
                                    <p><strong>Telefone:</strong> <?= $tomador['telefone'] ?? 'Não informado' ?></p>
                                    
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> Os endereços relacionados a este tomador (proprietário) aparecem na lista de imóveis ao lado.
                                    </div>
                                    
                                    <?php if(isset($tomador['observacoes']) && !empty($tomador['observacoes'])): ?>
                                    <div class="mt-3">
                                        <strong>Observações:</strong><br>
                                        <?= nl2br($tomador['observacoes']) ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                    <h6 class="card-title mb-0">Imóveis do Tomador</h6>
                                    <a href="<?= base_url('imoveis/create?tomador_id='.$tomador['id']) ?>" class="btn btn-light btn-sm">
                                        <i class="fas fa-plus"></i> Novo Imóvel
                                    </a>
                                </div>
                                <div class="card-body">
                                    <?php if(!empty($imoveis)): ?>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="imoveisTable">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Endereço</th>
                                                        <th>Inquilino</th>
                                                        <th>Valor Aluguel</th>
                                                        <th>Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($imoveis as $imovel): ?>
                                                        <tr>
                                                            <td>
                                                                <?= $imovel['endereco'] ?>
                                                                <?php if(!empty($imovel['numero'])): ?>, <?= $imovel['numero'] ?><?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if(!empty($imovel['inquilino_nome'])): ?>
                                                                    <span class="badge bg-success"><?= $imovel['inquilino_nome'] ?></span>
                                                                <?php else: ?>
                                                                    <span class="badge bg-secondary">Sem inquilino</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?= !empty($imovel['valor_aluguel']) ? 'R$ ' . number_format($imovel['valor_aluguel'], 2, ',', '.') : 'Não informado' ?>
                                                            </td>
                                                            <td>
                                                                <div class="btn-group btn-group-sm">
                                                                    <a href="<?= base_url('imoveis/view/'.$imovel['id']) ?>" class="btn btn-info" title="Visualizar">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                    <a href="<?= base_url('imoveis/edit/'.$imovel['id']) ?>" class="btn btn-primary" title="Editar">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> Este tomador não possui imóveis cadastrados.
                                            <div class="mt-2">
                                                <a href="<?= base_url('imoveis/create?tomador_id='.$tomador['id']) ?>" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-plus"></i> Cadastrar Imóvel
                                                </a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 d-flex justify-content-end">
                        <a href="<?= base_url('tomadores') ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Voltar para Lista
                        </a>
                        <a href="<?= base_url('tomadores/edit/'.$tomador['id']) ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Editar Tomador
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Função para formatar CPF/CNPJ
function mask($val, $mask) {
    $maskared = '';
    $k = 0;
    for($i = 0; $i <= strlen($mask)-1; $i++) {
        if($mask[$i] == '#') {
            if(isset($val[$k])) {
                $maskared .= $val[$k++];
            }
        } else {
            if(isset($mask[$i])) {
                $maskared .= $mask[$i];
            }
        }
    }
    return $maskared;
}
?>

<script>
$(document).ready(function() {
    $('#imoveisTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
        },
        "pageLength": 5,
        "responsive": true
    });
});
</script>
