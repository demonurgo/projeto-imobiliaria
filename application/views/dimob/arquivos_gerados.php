<div class="container">
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo site_url('dashboard'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo site_url('dimob'); ?>">DIMOB</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Arquivos Gerados - Ano <?php echo $ano; ?></li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0"><i class="fas fa-check-circle mr-2"></i>Arquivos DIMOB Gerados com Sucesso</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <p><i class="fas fa-info-circle mr-2"></i>Foram gerados <?php echo count($arquivos); ?> arquivos DIMOB para o ano de <?php echo $ano; ?>.</p>
                        <p class="mb-0">Selecione abaixo qual arquivo deseja baixar:</p>
                    </div>
                    
                    <div class="table-responsive mt-4">
                        <table class="table table-bordered table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Prestador</th>
                                    <th>Total de Notas</th>
                                    <th>Nome do Arquivo</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($arquivos as $arquivo): ?>
                                <tr>
                                    <td><?php echo $arquivo['prestador']; ?></td>
                                    <td><?php echo $arquivo['total_notas']; ?></td>
                                    <td><?php echo $arquivo['nome']; ?></td>
                                    <td>
                                        <a href="<?php echo base_url('uploads/dimob/' . $arquivo['nome']); ?>" class="btn btn-primary" download>
                                            <i class="fas fa-download mr-2"></i>Download
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 text-center">
                        <a href="<?php echo site_url('dimob'); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>Voltar para DIMOB
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos adicionais para a página de arquivos gerados */
    .card-header.bg-success {
        border-bottom: 0;
    }
    
    .table th, .table td {
        vertical-align: middle;
    }
    
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .btn-primary:hover {
        background-color: #0069d9;
        border-color: #0062cc;
    }
</style>

<script>
$(document).ready(function() {
    // Animação para os botões de download
    $('.btn-primary').hover(
        function() { $(this).addClass('shadow-sm'); },
        function() { $(this).removeClass('shadow-sm'); }
    );
});
</script>
