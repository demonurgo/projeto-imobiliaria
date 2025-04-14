<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-user-plus"></i> Cadastrar Novo Inquilino</h5>
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
                    
                    <?php echo form_open('inquilinos/create', ['class' => 'needs-validation', 'novalidate' => '']); ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome Completo *</label>
                                <input type="text" class="form-control" id="nome" name="nome" value="<?= set_value('nome') ?>" required>
                                <div class="invalid-feedback">O nome é obrigatório.</div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="tipo_documento" class="form-label">Tipo de Documento *</label>
                                <select class="form-select" id="tipo_documento" name="tipo_documento" required>
                                    <option value="cpf" <?= set_select('tipo_documento', 'cpf', TRUE) ?>>CPF</option>
                                    <option value="cnpj" <?= set_select('tipo_documento', 'cnpj') ?>>CNPJ</option>
                                </select>
                                <div class="invalid-feedback">Selecione o tipo de documento.</div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="documento" class="form-label">Número do Documento *</label>
                                <input type="text" class="form-control" id="documento" name="documento" value="<?= set_value('documento') ?>" required>
                                <div class="invalid-feedback">O documento é obrigatório.</div>
                                <div class="form-text" id="documentoHelp">Apenas números.</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control" id="telefone" name="telefone" value="<?= set_value('telefone') ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= set_value('email') ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="endereco" class="form-label">Endereço Completo</label>
                                <textarea class="form-control" id="endereco" name="endereco" rows="2"><?= set_value('endereco') ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="observacoes" class="form-label">Observações</label>
                                <textarea class="form-control" id="observacoes" name="observacoes" rows="3"><?= set_value('observacoes') ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="<?= base_url('inquilinos') ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Salvar
                        </button>
                    </div>
                    
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
        
        // Máscara e validação para CPF/CNPJ
        const tipoDocumento = document.getElementById('tipo_documento');
        const documento = document.getElementById('documento');
        const documentoHelp = document.getElementById('documentoHelp');
        
        function atualizarMascara() {
            if (tipoDocumento.value === 'cpf') {
                documentoHelp.textContent = 'CPF: Apenas números (11 dígitos).';
                documento.setAttribute('maxlength', '11');
                documento.setAttribute('pattern', '[0-9]{11}');
            } else {
                documentoHelp.textContent = 'CNPJ: Apenas números (14 dígitos).';
                documento.setAttribute('maxlength', '14');
                documento.setAttribute('pattern', '[0-9]{14}');
            }
        }
        
        // Aplicar a máscara inicial
        atualizarMascara();
        
        // Atualizar a máscara quando o tipo de documento mudar
        tipoDocumento.addEventListener('change', atualizarMascara);
        
        // Permitir apenas números no campo de documento
        documento.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });
    });
</script>
