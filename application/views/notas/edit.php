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
                    
                    <?php if(validation_errors()): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo validation_errors(); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php echo form_open('notas/edit/'.$nota['id'], ['class' => 'needs-validation', 'novalidate' => '']); ?>
                    
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
                                <label for="tomador_id" class="form-label">Tomador de Serviço</label>
                                <select class="form-select" id="tomador_id" name="tomador_id" required>
                                    <option value="">Selecione o tomador</option>
                                    <?php foreach($tomadores as $tomador): ?>
                                    <option value="<?= $tomador['id'] ?>" <?= ($tomador['id'] == $nota['tomador_id']) ? 'selected' : '' ?>>
                                        <?= $tomador['razao_social'] ?> - <?= $tomador['cpf_cnpj'] ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h4 class="border-bottom pb-2">Informações para DIMOB</h4>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inquilino_id" class="form-label">Inquilino</label>
                                <select class="form-select" id="inquilino_id" name="inquilino_id">
                                    <option value="">Selecione o inquilino (opcional)</option>
                                    <?php foreach($inquilinos as $inquilino): ?>
                                    <option value="<?= $inquilino['id'] ?>" <?= ($inquilino['id'] == $nota['inquilino_id']) ? 'selected' : '' ?>>
                                        <?= $inquilino['nome'] ?> - <?= $inquilino['cpf_cnpj'] ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Selecione o inquilino relacionado a esta nota ou cadastre manualmente abaixo.</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="imovel_id" class="form-label">Imóvel</label>
                                <select class="form-select" id="imovel_id" name="imovel_id">
                                    <option value="">Selecione o imóvel (opcional)</option>
                                    <?php foreach($imoveis as $imovel): ?>
                                    <option value="<?= $imovel['id'] ?>" <?= ($imovel['id'] == $nota['imovel_id']) ? 'selected' : '' ?>>
                                        <?= $imovel['endereco'] ?> <?php if(!empty($imovel['valor_aluguel'])): ?>(Aluguel: R$ <?= number_format($imovel['valor_aluguel'], 2, ',', '.') ?>)<?php endif; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Selecione o imóvel relacionado a esta nota.</div>
                            </div>
                        </div>
                        
                        <div class="col-md-12 mt-3 mb-4" id="cadastro-manual-inquilino">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Cadastro Manual de Inquilino</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">Preencha os campos abaixo se não encontrar o inquilino na lista acima.</p>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="inquilino_tipo_documento" class="form-label">Tipo de Documento</label>
                                                <select class="form-select" id="inquilino_tipo_documento" name="inquilino_tipo_documento">
                                                    <option value="cpf">CPF</option>
                                                    <option value="cnpj">CNPJ</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label for="inquilino_documento" class="form-label">CPF/CNPJ</label>
                                                <input type="text" class="form-control" id="inquilino_documento" name="inquilino_documento" placeholder="Somente números">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label for="inquilino_nome" class="form-label">Nome do Inquilino</label>
                                                <input type="text" class="form-control" id="inquilino_nome" name="inquilino_nome">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="valor_aluguel" class="form-label">Valor do Aluguel (R$)</label>
                                                <input type="number" class="form-control" id="valor_aluguel" name="valor_aluguel" step="0.01">
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
                                    <code>ENDEREÇO DO IMÓVEL</code>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="<?= base_url('notas') ?>" class="btn btn-secondary me-2">
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
        
        // Função para alternar a visibilidade do formulário manual de inquilino
        const inquilinoSelect = document.getElementById('inquilino_id');
        const manualInquilinoCard = document.getElementById('cadastro-manual-inquilino');
        
        function toggleManualInquilinoForm() {
            if (inquilinoSelect.value === '') {
                manualInquilinoCard.style.display = 'block';
            } else {
                manualInquilinoCard.style.display = 'none';
                // Limpar campos do formulário manual quando um inquilino é selecionado
                document.getElementById('inquilino_documento').value = '';
                document.getElementById('inquilino_nome').value = '';
            }
        }
        
        // Inicializar o estado do formulário
        toggleManualInquilinoForm();
        
        // Adicionar evento de mudança no select de inquilino
        inquilinoSelect.addEventListener('change', toggleManualInquilinoForm);
    });
</script>
