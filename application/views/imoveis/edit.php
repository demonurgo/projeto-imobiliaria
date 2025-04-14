<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-edit"></i> Editar Imóvel</h5>
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
                    
                    <?php echo form_open('imoveis/edit/'.$imovel['id'], ['class' => 'needs-validation', 'novalidate' => '']); ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="codigo_referencia" class="form-label">Código de Referência</label>
                                <input type="text" class="form-control" id="codigo_referencia" name="codigo_referencia" value="<?= set_value('codigo_referencia', $imovel['codigo_referencia']) ?>">
                                <div class="form-text">Se não informado, será gerado automaticamente.</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tomador_id" class="form-label">Proprietário do Imóvel</label>
                                <select class="form-select" id="tomador_id" name="tomador_id">
                                    <option value="">Selecione o proprietário (opcional)</option>
                                    <?php foreach ($tomadores as $tomador): ?>
                                    <option value="<?= $tomador['id'] ?>" <?= set_select('tomador_id', $tomador['id'], ($imovel['tomador_id'] == $tomador['id'])) ?>>
                                        <?= $tomador['razao_social'] ?> - <?= $tomador['cpf_cnpj'] ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">O proprietário do imóvel é o Tomador do serviço.</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inquilino_id" class="form-label">Inquilino</label>
                                <select class="form-select" id="inquilino_id" name="inquilino_id">
                                    <option value="">Selecione um inquilino (opcional)</option>
                                    <?php foreach ($inquilinos as $inquilino): ?>
                                    <option value="<?= $inquilino['id'] ?>" <?= set_select('inquilino_id', $inquilino['id'], ($imovel['inquilino_id'] == $inquilino['id'])) ?>>
                                        <?= $inquilino['nome'] ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="valor_aluguel" class="form-label">Valor do Aluguel (R$)</label>
                                <input type="number" class="form-control" id="valor_aluguel" name="valor_aluguel" value="<?= set_value('valor_aluguel', $imovel['valor_aluguel']) ?>" step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="endereco" class="form-label">Endereço *</label>
                                <input type="text" class="form-control" id="endereco" name="endereco" value="<?= set_value('endereco', $imovel['endereco']) ?>" required>
                                <div class="invalid-feedback">O endereço é obrigatório.</div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="numero" class="form-label">Número</label>
                                <input type="text" class="form-control" id="numero" name="numero" value="<?= set_value('numero', $imovel['numero']) ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="complemento" class="form-label">Complemento</label>
                                <input type="text" class="form-control" id="complemento" name="complemento" value="<?= set_value('complemento', $imovel['complemento']) ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bairro" class="form-label">Bairro</label>
                                <input type="text" class="form-control" id="bairro" name="bairro" value="<?= set_value('bairro', $imovel['bairro']) ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label for="cidade" class="form-label">Cidade</label>
                                <input type="text" class="form-control" id="cidade" name="cidade" value="<?= set_value('cidade', $imovel['cidade']) ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="uf" class="form-label">UF</label>
                                <select class="form-select" id="uf" name="uf">
                                    <option value="">Selecione</option>
                                    <option value="AC" <?= set_select('uf', 'AC', ($imovel['uf'] == 'AC')) ?>>AC</option>
                                    <option value="AL" <?= set_select('uf', 'AL', ($imovel['uf'] == 'AL')) ?>>AL</option>
                                    <option value="AP" <?= set_select('uf', 'AP', ($imovel['uf'] == 'AP')) ?>>AP</option>
                                    <option value="AM" <?= set_select('uf', 'AM', ($imovel['uf'] == 'AM')) ?>>AM</option>
                                    <option value="BA" <?= set_select('uf', 'BA', ($imovel['uf'] == 'BA')) ?>>BA</option>
                                    <option value="CE" <?= set_select('uf', 'CE', ($imovel['uf'] == 'CE')) ?>>CE</option>
                                    <option value="DF" <?= set_select('uf', 'DF', ($imovel['uf'] == 'DF')) ?>>DF</option>
                                    <option value="ES" <?= set_select('uf', 'ES', ($imovel['uf'] == 'ES')) ?>>ES</option>
                                    <option value="GO" <?= set_select('uf', 'GO', ($imovel['uf'] == 'GO')) ?>>GO</option>
                                    <option value="MA" <?= set_select('uf', 'MA', ($imovel['uf'] == 'MA')) ?>>MA</option>
                                    <option value="MT" <?= set_select('uf', 'MT', ($imovel['uf'] == 'MT')) ?>>MT</option>
                                    <option value="MS" <?= set_select('uf', 'MS', ($imovel['uf'] == 'MS')) ?>>MS</option>
                                    <option value="MG" <?= set_select('uf', 'MG', ($imovel['uf'] == 'MG')) ?>>MG</option>
                                    <option value="PA" <?= set_select('uf', 'PA', ($imovel['uf'] == 'PA')) ?>>PA</option>
                                    <option value="PB" <?= set_select('uf', 'PB', ($imovel['uf'] == 'PB')) ?>>PB</option>
                                    <option value="PR" <?= set_select('uf', 'PR', ($imovel['uf'] == 'PR')) ?>>PR</option>
                                    <option value="PE" <?= set_select('uf', 'PE', ($imovel['uf'] == 'PE')) ?>>PE</option>
                                    <option value="PI" <?= set_select('uf', 'PI', ($imovel['uf'] == 'PI')) ?>>PI</option>
                                    <option value="RJ" <?= set_select('uf', 'RJ', ($imovel['uf'] == 'RJ')) ?>>RJ</option>
                                    <option value="RN" <?= set_select('uf', 'RN', ($imovel['uf'] == 'RN')) ?>>RN</option>
                                    <option value="RS" <?= set_select('uf', 'RS', ($imovel['uf'] == 'RS')) ?>>RS</option>
                                    <option value="RO" <?= set_select('uf', 'RO', ($imovel['uf'] == 'RO')) ?>>RO</option>
                                    <option value="RR" <?= set_select('uf', 'RR', ($imovel['uf'] == 'RR')) ?>>RR</option>
                                    <option value="SC" <?= set_select('uf', 'SC', ($imovel['uf'] == 'SC')) ?>>SC</option>
                                    <option value="SP" <?= set_select('uf', 'SP', ($imovel['uf'] == 'SP')) ?>>SP</option>
                                    <option value="SE" <?= set_select('uf', 'SE', ($imovel['uf'] == 'SE')) ?>>SE</option>
                                    <option value="TO" <?= set_select('uf', 'TO', ($imovel['uf'] == 'TO')) ?>>TO</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="cep" class="form-label">CEP</label>
                                <input type="text" class="form-control" id="cep" name="cep" value="<?= set_value('cep', $imovel['cep']) ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="observacoes" class="form-label">Observações</label>
                                <textarea class="form-control" id="observacoes" name="observacoes" rows="3"><?= set_value('observacoes', $imovel['observacoes']) ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="<?= base_url('imoveis') ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Salvar Alterações
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
        
        // Máscara para CEP
        $('#cep').on('input', function() {
            var cep = $(this).val().replace(/\D/g, '');
            if (cep.length > 8) {
                cep = cep.substring(0, 8);
            }
            
            if (cep.length > 5) {
                cep = cep.substring(0, 5) + '-' + cep.substring(5);
            }
            
            $(this).val(cep);
        });
    });
</script>
