<style>
.table-spacing tbody tr {
    height: 60px;
}
.table-spacing td {
    vertical-align: middle;
    padding: 10px !important;
}
.dataTables_wrapper {
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 5px;
}
</style>
<div class="container">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.16/dist/jquery.mask.min.js"></script>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title"><i class="fas fa-file-alt mr-2"></i>Geração de Arquivo DIMOB</h3>
                </div>
                <div class="card-body">
                    <?php if($this->session->flashdata('success')): ?>
                        <div class="alert alert-success">
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="alert alert-info mb-4">
                        <h5><i class="icon fas fa-info-circle"></i> O que é o DIMOB?</h5>
                        <p>A Declaração de Informações sobre Atividades Imobiliárias (DIMOB) é uma obrigação acessória que deve ser entregue à Receita Federal pelas pessoas jurídicas que comercializam imóveis, realizam intermediação na compra, venda ou aluguel de imóveis.</p>
                        <p class="mb-0">Este módulo permite gerar o arquivo TXT no formato exigido pela Receita Federal para importação no programa DIMOB.</p>
                    </div>
                    
                    <div class="card card-outline card-secondary mb-4">
                        <div class="card-body">
                            <?php echo form_open('dimob/listar', ['class' => 'form-horizontal']); ?>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="ano" class="form-label font-weight-bold">Ano de Referência:</label>
                                        <select name="ano" id="ano" class="form-control form-control-lg" required>
                                            <option value="">Selecione o ano</option>
                                            <?php foreach($anos_disponiveis as $ano): ?>
                                                <option value="<?php echo $ano; ?>"><?php echo $ano; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-primary btn-lg px-5">
                                            <i class="fas fa-search mr-2"></i> Buscar Notas Fiscais
                                        </button>
                                    </div>
                                </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS adicional para corrigir problemas de estilo -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style>
    /* Estilos personalizados para o módulo DIMOB */
    .card-header.bg-primary {
        border-bottom: 0;
    }
    
    .form-label {
        margin-bottom: 0.5rem;
        color: #495057;
    }
    
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 46px;
        padding-top: 8px;
    }
    
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
    }
    
    .form-control-lg {
        height: calc(1.5em + 1rem + 2px);
        font-size: 1.1rem;
    }
</style>

<!-- JavaScript para a página -->
<script src="<?php echo base_url('assets/js/dimob.js'); ?>"></script>

<script>
$(document).ready(function() {
    // Inicializar select2 com tema e configurações melhoradas
    $('#ano').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('body'),
        width: '100%',
        placeholder: 'Selecione uma opção',
        allowClear: true
    });
    
    // Adicionar animação suave ao botão
    $('.btn-primary').hover(
        function() { $(this).addClass('shadow-sm'); },
        function() { $(this).removeClass('shadow-sm'); }
    );
});
</script>
