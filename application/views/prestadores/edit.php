<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-edit"></i> Editar Prestador</h5>
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
                    
                    <?= form_open('prestadores/edit/'.$prestador['id'], ['class' => 'needs-validation', 'novalidate' => '']); ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cpf" class="form-label">CPF</label>
                                <?php 
                                $cpf = isset($prestador['cpf']) ? $prestador['cpf'] : '';
                                if(strlen($cpf) === 11) {
                                    $cpf_formatado = substr($cpf, 0, 3).'.'.substr($cpf, 3, 3).'.'.substr($cpf, 6, 3).'-'.substr($cpf, 9, 2);
                                } else {
                                    $cpf_formatado = $cpf;
                                }
                                ?>
                                <input type="text" class="form-control" id="cpf" name="cpf" value="<?= set_value('cpf', $cpf_formatado) ?>">
                                <div class="form-text">CPF do prestador (se pessoa física)</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cnpj" class="form-label">CNPJ</label>
                                <?php 
                                $cnpj = isset($prestador['cnpj']) ? $prestador['cnpj'] : '';
                                if(strlen($cnpj) === 14) {
                                    $cnpj_formatado = substr($cnpj, 0, 2).'.'.substr($cnpj, 2, 3).'.'.substr($cnpj, 5, 3).'/'.substr($cnpj, 8, 4).'-'.substr($cnpj, 12, 2);
                                } else {
                                    $cnpj_formatado = $cnpj;
                                }
                                ?>
                                <input type="text" class="form-control" id="cnpj" name="cnpj" value="<?= set_value('cnpj', $cnpj_formatado) ?>">
                                <div class="form-text">CNPJ do prestador (se pessoa jurídica)</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inscricao_municipal" class="form-label">Inscrição Municipal</label>
                                <input type="text" class="form-control" id="inscricao_municipal" name="inscricao_municipal" value="<?= set_value('inscricao_municipal', $prestador['inscricao_municipal']) ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="razao_social" class="form-label">Razão Social / Nome *</label>
                                <input type="text" class="form-control" id="razao_social" name="razao_social" value="<?= set_value('razao_social', $prestador['razao_social']) ?>" required>
                                <div class="invalid-feedback">Razão Social é obrigatória</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= set_value('email', $prestador['email']) ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control" id="telefone" name="telefone" value="<?= set_value('telefone', $prestador['telefone']) ?>">
                            </div>
                        </div>
                    </div>
                    
                    <h6 class="mt-4 mb-3">Endereço</h6>
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="endereco" class="form-label">Logradouro</label>
                                <input type="text" class="form-control" id="endereco" name="endereco" value="<?= set_value('endereco', $prestador['endereco']) ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="numero" class="form-label">Número</label>
                                <input type="text" class="form-control" id="numero" name="numero" value="<?= set_value('numero', $prestador['numero']) ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="complemento" class="form-label">Complemento</label>
                                <input type="text" class="form-control" id="complemento" name="complemento" value="<?= set_value('complemento', $prestador['complemento']) ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bairro" class="form-label">Bairro</label>
                                <input type="text" class="form-control" id="bairro" name="bairro" value="<?= set_value('bairro', $prestador['bairro']) ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cidade" class="form-label">Cidade</label>
                                <input type="text" class="form-control" id="cidade" name="cidade" value="<?= set_value('cidade', isset($prestador['cidade']) ? $prestador['cidade'] : '') ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="uf" class="form-label">UF</label>
                                <select class="form-select" id="uf" name="uf">
                                    <option value="">Selecione</option>
                                    <?php
                                    $estados = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 
                                               'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 
                                               'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];
                                    foreach($estados as $estado) {
                                        $selected = ($prestador['uf'] == $estado) ? 'selected' : '';
                                        echo "<option value=\"{$estado}\" {$selected}>{$estado}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="cep" class="form-label">CEP</label>
                                <?php 
                                $cep = $prestador['cep'];
                                if(strlen($cep) === 8) {
                                    $cep_formatado = substr($cep, 0, 5).'-'.substr($cep, 5, 3);
                                } else {
                                    $cep_formatado = $cep;
                                }
                                ?>
                                <input type="text" class="form-control" id="cep" name="cep" value="<?= set_value('cep', $cep_formatado) ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="codigo_municipio" class="form-label">Código do Município (IBGE)</label>
                                <input type="text" class="form-control" id="codigo_municipio" name="codigo_municipio" value="<?= set_value('codigo_municipio', $prestador['codigo_municipio']) ?>">
                                <div class="form-text">Código de 7 dígitos do IBGE</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="<?= base_url('prestadores') ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Atualizar
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