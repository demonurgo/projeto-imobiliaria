<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-plus-circle"></i> Cadastrar Novo Tomador</h5>
                </div>
                <div class="card-body">
                    <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(validation_errors()): ?>
                    <div class="alert alert-warning alert-dismissible fade show">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo validation_errors(); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    
                    <?= form_open('tomadores/create', ['class' => 'needs-validation', 'novalidate' => '']); ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Documento</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_documento" id="tipoCPF" value="cpf" checked>
                                    <label class="form-check-label" for="tipoCPF">
                                        CPF (Pessoa Física)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_documento" id="tipoCNPJ" value="cnpj">
                                    <label class="form-check-label" for="tipoCNPJ">
                                        CNPJ (Pessoa Jurídica)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3" id="cpfGroup">
                                <label for="cpf" class="form-label">CPF *</label>
                                <input type="text" class="form-control" id="cpf" name="cpf" placeholder="000.000.000-00" required>
                                <div class="invalid-feedback">CPF é obrigatório</div>
                            </div>
                            
                            <div class="mb-3" id="cnpjGroup" style="display: none;">
                                <label for="cnpj" class="form-label">CNPJ *</label>
                                <input type="text" class="form-control" id="cnpj" name="cnpj" placeholder="00.000.000/0000-00">
                                <div class="invalid-feedback">CNPJ é obrigatório</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="razao_social" class="form-label">Nome/Razão Social *</label>
                                <input type="text" class="form-control" id="razao_social" name="razao_social" required>
                                <div class="invalid-feedback">Nome/Razão Social é obrigatório</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control" id="telefone" name="telefone">
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle"></i> O endereço para um tomador de serviço aparecerá nos dados do imóvel. Aqui registramos apenas os dados de contato do proprietário.
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="observacoes" class="form-label">Observações</label>
                                <textarea class="form-control" id="observacoes" name="observacoes" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="<?= base_url('tomadores') ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Salvar
                        </button>
                    </div>
                    
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Inicializar máscaras
    $('#cpf').mask('000.000.000-00');
    $('#cnpj').mask('00.000.000/0000-00');
    $('#telefone').mask('(00) 00000-0000');
    
    // Alternar entre CPF e CNPJ
    $('input[name="tipo_documento"]').change(function() {
        if($(this).val() === 'cpf') {
            $('#cpfGroup').show();
            $('#cnpjGroup').hide();
            $('#cpf').attr('required', true);
            $('#cnpj').attr('required', false);
            $('#cnpj').val('');
        } else {
            $('#cpfGroup').hide();
            $('#cnpjGroup').show();
            $('#cpf').attr('required', false);
            $('#cnpj').attr('required', true);
            $('#cpf').val('');
        }
    });
    
    // Validação do formulário
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});
</script>