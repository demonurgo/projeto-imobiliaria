<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-edit"></i> Editar Nota Fiscal #<?= $nota['numero'] ?></h5>
                </div>
                <div class="card-body">
                    <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(isset($nota['status']) && $nota['status'] === 'revisar'): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Atenção:</strong> O CPF/CNPJ do tomador (proprietário) é igual ao do inquilino (locatário). Isso pode indicar um erro de cadastro. Por favor, verifique os dados.
                    </div>
                    <?php endif; ?>
                    
                    <?php if(validation_errors()): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo validation_errors(); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php echo form_open('importacao/editar/'.$nota['id'], ['class' => 'needs-validation', 'novalidate' => '']); ?>
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h4 class="border-bottom pb-2">Informações da Nota Fiscal</h4>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="numero" class="form-label">Número</label>
                                <input type="text" class="form-control" id="numero" name="numero" value="<?= $nota['numero'] ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="codigo_verificacao" class="form-label">Código Verificação</label>
                                <input type="text" class="form-control" id="codigo_verificacao" name="codigo_verificacao" value="<?= $nota['codigo_verificacao'] ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="data_emissao" class="form-label">Data Emissão</label>
                                <input type="datetime-local" class="form-control" id="data_emissao" name="data_emissao" value="<?= str_replace(' ', 'T', $nota['data_emissao']) ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="valor_servicos" class="form-label">Valor Serviços (R$)</label>
                                <input type="number" class="form-control" id="valor_servicos" name="valor_servicos" value="<?= $nota['valor_servicos'] ?>" step="0.01" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="valor_iss" class="form-label">Valor ISS (R$)</label>
                                <input type="number" class="form-control" id="valor_iss" name="valor_iss" value="<?= $nota['valor_iss'] ?>" step="0.01">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="base_calculo" class="form-label">Base Cálculo (R$)</label>
                                <input type="number" class="form-control" id="base_calculo" name="base_calculo" value="<?= $nota['base_calculo'] ?>" step="0.01">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="aliquota" class="form-label">Alíquota (%)</label>
                                <input type="number" class="form-control" id="aliquota" name="aliquota" value="<?= $nota['aliquota'] ?>" step="0.01">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h4 class="border-bottom pb-2">Prestador e Tomador</h4>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prestador_id" class="form-label">Prestador de Serviço</label>
                                <select class="form-select" id="prestador_id" name="prestador_id" required>
                                    <option value="">Selecione o prestador</option>
                                    <?php foreach($prestadores as $prestador): ?>
                                    <option value="<?= $prestador['id'] ?>" <?= ($prestador['id'] == $nota['prestador_id']) ? 'selected' : '' ?>>
                                        <?= $prestador['razao_social'] ?> - <?= $prestador['cnpj'] ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tomador_id" class="form-label">Tomador de Serviço (Proprietário)</label>
                                <select class="form-select" id="tomador_id" name="tomador_id" required>
                                    <option value="">Selecione o tomador</option>
                                    <?php foreach($tomadores as $tomador): ?>
                                    <option value="<?= $tomador['id'] ?>" 
                                        data-cpf-cnpj="<?= $tomador['cpf_cnpj'] ?>" 
                                        <?= ($tomador['id'] == $nota['tomador_id']) ? 'selected' : '' ?>>
                                        <?= $tomador['razao_social'] ?> - <?= $tomador['cpf_cnpj'] ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">O CPF/CNPJ do tomador é exibido na listagem como "CPF/CNPJ do Proprietário".</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h4 class="border-bottom pb-2">Informações para DIMOB</h4>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Dados do Inquilino</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="inquilino_id" class="form-label">Selecionar Inquilino Existente (opcional)</label>
                                            <select class="form-select" id="inquilino_id" name="inquilino_id">
                                                <option value="">Nenhum - Usar dados abaixo</option>
                                                <?php foreach($inquilinos as $inquilino): ?>
                                                <option value="<?= $inquilino['id'] ?>" 
                                                    data-nome="<?= $inquilino['nome'] ?>" 
                                                    data-documento="<?= $inquilino['cpf_cnpj'] ?>"
                                                    <?= ($inquilino['id'] == $nota['inquilino_id']) ? 'selected' : '' ?>>
                                                    <?= $inquilino['nome'] ?> - <?= $inquilino['cpf_cnpj'] ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="form-text">Selecionar um inquilino preencherá automaticamente os campos abaixo.</div>
                                        </div>
                                        
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label for="inquilino_nome" class="form-label">Nome do Inquilino</label>
                                                <input type="text" class="form-control" id="inquilino_nome" name="inquilino_nome" value="<?= isset($nota['inquilino_nome']) ? $nota['inquilino_nome'] : '' ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="inquilino_documento" class="form-label">CPF/CNPJ</label>
                                                <input type="text" class="form-control" id="inquilino_documento" name="inquilino_documento" placeholder="Somente números" value="<?= isset($nota['inquilino_cpf_cnpj']) ? $nota['inquilino_cpf_cnpj'] : '' ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Dados do Imóvel</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="imovel_id" class="form-label">Selecionar Imóvel Existente (opcional)</label>
                                            <select class="form-select" id="imovel_id" name="imovel_id">
                                                <option value="">Nenhum - Usar dados abaixo</option>
                                                <?php foreach($imoveis as $imovel): ?>
                                                <option value="<?= $imovel['id'] ?>"
                                                    data-endereco="<?= $imovel['endereco'] ?>"
                                                    data-numero="<?= $imovel['numero'] ?>"
                                                    data-complemento="<?= $imovel['complemento'] ?>"
                                                    data-cidade="<?= $imovel['cidade'] ?>"
                                                    data-uf="<?= $imovel['uf'] ?>"
                                                    data-cep="<?= $imovel['cep'] ?>"
                                                    data-valor="<?= $imovel['valor_aluguel'] ?>"
                                                    <?= ($imovel['id'] == $nota['imovel_id']) ? 'selected' : '' ?>>
                                                    <?= $imovel['endereco'] ?> <?php if(!empty($imovel['numero'])): ?>, <?= $imovel['numero'] ?><?php endif; ?> <?php if(!empty($imovel['complemento'])): ?> - <?= $imovel['complemento'] ?><?php endif; ?> <?php if(!empty($imovel['valor_aluguel'])): ?>(Aluguel: R$ <?= number_format($imovel['valor_aluguel'], 2, ',', '.') ?>)<?php endif; ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="form-text">Selecionar um imóvel preencherá automaticamente os campos abaixo.</div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="imovel_endereco" class="form-label">Endereço do Imóvel</label>
                                                <input type="text" class="form-control" id="imovel_endereco" name="imovel_endereco" value="<?= isset($nota['imovel_endereco']) ? $nota['imovel_endereco'] : '' ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="imovel_numero" class="form-label">Número</label>
                                                <input type="text" class="form-control" id="imovel_numero" name="imovel_numero" value="<?= isset($nota['imovel_numero']) ? $nota['imovel_numero'] : '' ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="imovel_complemento" class="form-label">Complemento</label>
                                                <input type="text" class="form-control" id="imovel_complemento" name="imovel_complemento" value="<?= isset($nota['imovel_complemento']) ? $nota['imovel_complemento'] : '' ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="imovel_cidade" class="form-label">Cidade</label>
                                                <input type="text" class="form-control" id="imovel_cidade" name="imovel_cidade" value="<?= isset($nota['imovel_cidade']) ? $nota['imovel_cidade'] : '' ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="imovel_uf" class="form-label">UF</label>
                                                <input type="text" class="form-control" id="imovel_uf" name="imovel_uf" maxlength="2" value="<?= isset($nota['imovel_uf']) ? $nota['imovel_uf'] : '' ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="imovel_cep" class="form-label">CEP</label>
                                                <input type="text" class="form-control" id="imovel_cep" name="imovel_cep" value="<?= isset($nota['imovel_cep']) ? $nota['imovel_cep'] : '' ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="valor_aluguel" class="form-label">Valor do Aluguel (R$)</label>
                                                <input type="number" class="form-control" id="valor_aluguel" name="valor_aluguel" step="0.01" value="<?= isset($nota['valor_aluguel']) ? $nota['valor_aluguel'] : '' ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="descricao_servico" class="form-label">Descrição do Serviço</label>
                                <input type="text" class="form-control" id="descricao_servico" name="descricao_servico" value="<?= $nota['descricao_servico'] ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="discriminacao" class="form-label">Discriminação Original</label>
                                <textarea class="form-control" id="discriminacao" name="discriminacao" rows="4"><?= $nota['discriminacao'] ?></textarea>
                                <div class="form-text">
                                    Este é o campo original "Discriminação" do XML. Formato esperado:
                                    <code>#CPF_OU_CNPJ#NOME_INQUILINO#VALOR_ALUGUEL# DESCRIÇÃO DO SERVIÇO</code>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="<?= base_url('importacao/revisar/'.$nota['batch_id']) ?>" class="btn btn-secondary me-2">
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
    // Ativando validação do formulário
    document.addEventListener('DOMContentLoaded', function() {
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
        
        // Função para preencher automaticamente os campos do inquilino
        const inquilinoSelect = document.getElementById('inquilino_id');
        const inquilinoNome = document.getElementById('inquilino_nome');
        const inquilinoDocumento = document.getElementById('inquilino_documento');
        
        function updateInquilinoFields() {
            if (inquilinoSelect.value === '') {
                // Não limpar automaticamente para permitir edição manual
            } else {
                // Preencher com os dados do inquilino selecionado
                const selectedOption = inquilinoSelect.options[inquilinoSelect.selectedIndex];
                inquilinoNome.value = selectedOption.getAttribute('data-nome') || '';
                inquilinoDocumento.value = selectedOption.getAttribute('data-documento') || '';
            }
        }
        
        // Função para preencher automaticamente os campos do imóvel
        const imovelSelect = document.getElementById('imovel_id');
        const imovelEndereco = document.getElementById('imovel_endereco');
        const imovelNumero = document.getElementById('imovel_numero');
        const imovelComplemento = document.getElementById('imovel_complemento');
        const imovelCidade = document.getElementById('imovel_cidade');
        const imovelUf = document.getElementById('imovel_uf');
        const imovelCep = document.getElementById('imovel_cep');
        const valorAluguel = document.getElementById('valor_aluguel');
        
        function updateImovelFields() {
            if (imovelSelect.value === '') {
                // Não limpar automaticamente para permitir edição manual
            } else {
                // Preencher com os dados do imóvel selecionado
                const selectedOption = imovelSelect.options[imovelSelect.selectedIndex];
                imovelEndereco.value = selectedOption.getAttribute('data-endereco') || '';
                imovelNumero.value = selectedOption.getAttribute('data-numero') || '';
                imovelComplemento.value = selectedOption.getAttribute('data-complemento') || '';
                imovelCidade.value = selectedOption.getAttribute('data-cidade') || '';
                imovelUf.value = selectedOption.getAttribute('data-uf') || '';
                imovelCep.value = selectedOption.getAttribute('data-cep') || '';
                valorAluguel.value = selectedOption.getAttribute('data-valor') || '';
            }
        }
        
        // Inicializar campos com base nas seleções atuais
        updateInquilinoFields();
        updateImovelFields();
        
        // Adicionar eventos de mudança
        inquilinoSelect.addEventListener('change', updateInquilinoFields);
        imovelSelect.addEventListener('change', updateImovelFields);
    });
</script>
