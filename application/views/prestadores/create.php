<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-plus-circle"></i> Cadastrar Novo Prestador</h5>
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
                    
                    <?= form_open('prestadores/create', ['class' => 'needs-validation', 'novalidate' => '']); ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cpf" class="form-label">CPF</label>
                                <input type="text" class="form-control" id="cpf" name="cpf" value="<?= set_value('cpf') ?>">
                                <div class="form-text">CPF do prestador (se pessoa física)</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cnpj" class="form-label">CNPJ</label>
                                <input type="text" class="form-control" id="cnpj" name="cnpj" value="<?= set_value('cnpj') ?>">
                                <div class="form-text">CNPJ do prestador (se pessoa jurídica)</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inscricao_municipal" class="form-label">Inscrição Municipal</label>
                                <input type="text" class="form-control" id="inscricao_municipal" name="inscricao_municipal" value="<?= set_value('inscricao_municipal') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="razao_social" class="form-label">Razão Social / Nome *</label>
                                <input type="text" class="form-control" id="razao_social" name="razao_social" value="<?= set_value('razao_social') ?>" required>
                                <div class="invalid-feedback">Razão Social é obrigatória</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= set_value('email') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control" id="telefone" name="telefone" value="<?= set_value('telefone') ?>">
                            </div>
                        </div>
                    </div>
                    
                    <h6 class="mt-4 mb-3">Endereço</h6>
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="endereco" class="form-label">Logradouro</label>
                                <input type="text" class="form-control" id="endereco" name="endereco" value="<?= set_value('endereco') ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="numero" class="form-label">Número</label>
                                <input type="text" class="form-control" id="numero" name="numero" value="<?= set_value('numero') ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="complemento" class="form-label">Complemento</label>
                                <input type="text" class="form-control" id="complemento" name="complemento" value="<?= set_value('complemento') ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bairro" class="form-label">Bairro</label>
                                <input type="text" class="form-control" id="bairro" name="bairro" value="<?= set_value('bairro') ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cidade" class="form-label">Cidade</label>
                                <input type="text" class="form-control" id="cidade" name="cidade" value="<?= set_value('cidade') ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="uf" class="form-label">UF</label>
                                <select class="form-select" id="uf" name="uf">
                                    <option value="">Selecione</option>
                                    <option value="AC" <?= set_select('uf', 'AC') ?>>AC</option>
                                    <option value="AL" <?= set_select('uf', 'AL') ?>>AL</option>
                                    <option value="AP" <?= set_select('uf', 'AP') ?>>AP</option>
                                    <option value="AM" <?= set_select('uf', 'AM') ?>>AM</option>
                                    <option value="BA" <?= set_select('uf', 'BA') ?>>BA</option>
                                    <option value="CE" <?= set_select('uf', 'CE') ?>>CE</option>
                                    <option value="DF" <?= set_select('uf', 'DF') ?>>DF</option>
                                    <option value="ES" <?= set_select('uf', 'ES') ?>>ES</option>
                                    <option value="GO" <?= set_select('uf', 'GO') ?>>GO</option>
                                    <option value="MA" <?= set_select('uf', 'MA') ?>>MA</option>
                                    <option value="MT" <?= set_select('uf', 'MT') ?>>MT</option>
                                    <option value="MS" <?= set_select('uf', 'MS') ?>>MS</option>
                                    <option value="MG" <?= set_select('uf', 'MG') ?>>MG</option>
                                    <option value="PA" <?= set_select('uf', 'PA') ?>>PA</option>
                                    <option value="PB" <?= set_select('uf', 'PB') ?>>PB</option>
                                    <option value="PR" <?= set_select('uf', 'PR') ?>>PR</option>
                                    <option value="PE" <?= set_select('uf', 'PE') ?>>PE</option>
                                    <option value="PI" <?= set_select('uf', 'PI') ?>>PI</option>
                                    <option value="RJ" <?= set_select('uf', 'RJ') ?>>RJ</option>
                                    <option value="RN" <?= set_select('uf', 'RN') ?>>RN</option>
                                    <option value="RS" <?= set_select('uf', 'RS') ?>>RS</option>
                                    <option value="RO" <?= set_select('uf', 'RO') ?>>RO</option>
                                    <option value="RR" <?= set_select('uf', 'RR') ?>>RR</option>
                                    <option value="SC" <?= set_select('uf', 'SC') ?>>SC</option>
                                    <option value="SP" <?= set_select('uf', 'SP') ?>>SP</option>
                                    <option value="SE" <?= set_select('uf', 'SE') ?>>SE</option>
                                    <option value="TO" <?= set_select('uf', 'TO') ?>>TO</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="cep" class="form-label">CEP</label>
                                <input type="text" class="form-control" id="cep" name="cep" value="<?= set_value('cep') ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="codigo_municipio" class="form-label">Código do Município (IBGE)</label>
                                <input type="text" class="form-control" id="codigo_municipio" name="codigo_municipio" value="<?= set_value('codigo_municipio') ?>">
                                <div class="form-text">Código de 7 dígitos do IBGE</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="<?= base_url('prestadores') ?>" class="btn btn-secondary me-2">
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
    $('#cep').mask('00000-000');
    
    // Buscar endereço pelo CEP
    $('#cep').blur(function() {
        var cep = $(this).val().replace(/\D/g, '');
        if (cep.length === 8) {
            $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                if (!("erro" in dados)) {
                    $("#endereco").val(dados.logradouro);
                    $("#bairro").val(dados.bairro);
                    $("#cidade").val(dados.localidade);
                    $("#uf").val(dados.uf);
                    
                    // Buscar código do município pelo nome da cidade e UF
                    if (dados.ibge) {
                        $("#codigo_municipio").val(dados.ibge);
                    }
                    
                    $("#numero").focus();
                }
            });
        }
    });
    
    // Validação do formulário
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            // Verificar se ao menos um documento foi preenchido
            var cpf = $('#cpf').val().replace(/\D/g, '');
            var cnpj = $('#cnpj').val().replace(/\D/g, '');
            
            if (cpf === '' && cnpj === '') {
                alert('É necessário informar ao menos um documento (CPF ou CNPJ)');
                event.preventDefault();
                event.stopPropagation();
            }
            
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});
</script>