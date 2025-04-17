<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-edit"></i> Editar Nota Fiscal #<?= $nota['numero'] ?> para DIMOB</h5>
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
                    
                    <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> As alterações realizadas nesta página serão utilizadas para a geração do arquivo DIMOB. Os dados são essenciais para a correta declaração à Receita Federal.
                    </div>
                    
                    <?php echo form_open('dimob/atualizar_nota', ['class' => 'needs-validation', 'novalidate' => '']); ?>
                    <input type="hidden" name="id" value="<?php echo $nota['id']; ?>">
                    <input type="hidden" name="ano" value="<?php echo date('Y', strtotime($nota['competencia'])); ?>">
                    
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
                                <input type="date" class="form-control" id="data_emissao" name="data_emissao" value="<?= date('Y-m-d', strtotime($nota['data_emissao'])) ?>" required>
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
                                <label for="competencia" class="form-label">Competência</label>
                                <input type="month" class="form-control" id="competencia" name="competencia" value="<?= date('Y-m', strtotime($nota['competencia'])) ?>">
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
                                                    data-documento="<?= isset($inquilino['cpf_cnpj']) ? $inquilino['cpf_cnpj'] : '' ?>"
                                                    data-email="<?= isset($inquilino['email']) ? $inquilino['email'] : '' ?>"
                                                    <?= ($inquilino['id'] == $nota['inquilino_id']) ? 'selected' : '' ?>>
                                                    <?= $inquilino['nome'] ?> - <?= isset($inquilino['cpf_cnpj']) ? $inquilino['cpf_cnpj'] : '' ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="form-text">Selecionar um inquilino preencherá automaticamente os campos abaixo.</div>
                                        </div>
                                        
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label for="inquilino_nome" class="form-label">Nome do Inquilino</label>
                                                <input type="text" class="form-control" id="inquilino_nome" name="inquilino_nome" 
                                                       value="<?= !empty($nota['inquilino_id']) ? (isset($nota['inquilino_nome']) ? $nota['inquilino_nome'] : 
                                                       (isset($inquilino['nome']) ? $inquilino['nome'] : '')) : '' ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="inquilino_cpf_cnpj" class="form-label">CPF/CNPJ</label>
                                                <input type="text" class="form-control" id="inquilino_cpf_cnpj" name="inquilino_cpf_cnpj" placeholder="Somente números" 
                                                       value="<?= !empty($nota['inquilino_id']) ? (isset($nota['inquilino_cpf_cnpj']) ? $nota['inquilino_cpf_cnpj'] : 
                                                       (isset($inquilino['cpf_cnpj']) ? $inquilino['cpf_cnpj'] : '')) : '' ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="inquilino_email" class="form-label">E-mail</label>
                                                <input type="email" class="form-control" id="inquilino_email" name="inquilino_email" 
                                                       value="<?= !empty($nota['inquilino_id']) ? (isset($inquilino['email']) ? $inquilino['email'] : '') : '' ?>">
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
                                            <label for="imovel_id" class="form-label">Imóvel Selecionado</label>
                                            <input type="hidden" name="imovel_id" id="imovel_id" value="<?= $nota['imovel_id'] ?>">
                                            <?php 
                                            $endereco_completo = '';
                                            if(isset($imovel_atual)) {
                                                $endereco_completo = $imovel_atual['endereco'];
                                                if(!empty($imovel_atual['numero'])) $endereco_completo .= ', ' . $imovel_atual['numero'];
                                                if(!empty($imovel_atual['bairro'])) $endereco_completo .= ' - ' . $imovel_atual['bairro'];
                                                if(!empty($imovel_atual['cidade'])) $endereco_completo .= ' - ' . $imovel_atual['cidade'] . '/' . $imovel_atual['uf'];
                                            }
                                            ?>
                                            <div class="form-control bg-light"><?= $endereco_completo ?></div>
                                            <div class="form-text">Imóvel associado a esta nota fiscal. Para alterar o imóvel, edite o registro da nota no módulo principal.</div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="valor_aluguel" class="form-label">Valor do Aluguel (R$)</label>
                                                <input type="number" class="form-control" id="valor_aluguel" name="valor_aluguel" step="0.01" 
                                                    value="<?= isset($nota['valor_aluguel']) ? $nota['valor_aluguel'] : 
                                                        (isset($imovel_atual['valor_aluguel']) ? $imovel_atual['valor_aluguel'] : '') ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="imovel_tipo" class="form-label">Tipo do Imóvel</label>
                                                <select name="tipo_imovel" id="imovel_tipo" class="form-select">
                                                    <option value="urbano" <?= (isset($nota['tipo_imovel']) && $nota['tipo_imovel'] == 'urbano') || (isset($imovel_atual['tipo_imovel']) && $imovel_atual['tipo_imovel'] == 'urbano') ? 'selected' : '' ?>>Urbano</option>
                                                    <option value="rural" <?= (isset($nota['tipo_imovel']) && $nota['tipo_imovel'] == 'rural') || (isset($imovel_atual['tipo_imovel']) && $imovel_atual['tipo_imovel'] == 'rural') ? 'selected' : '' ?>>Rural</option>
                                                </select>
                                                <div class="form-text">Esta informação é obrigatória para o DIMOB</div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="data_inicio_locacao" class="form-label">Data de Início da Locação</label>
                                                <input type="date" class="form-control" id="data_inicio_locacao" name="data_inicio_locacao" 
                                                       value="<?= isset($nota['data_inicio_locacao']) ? date('Y-m-d', strtotime($nota['data_inicio_locacao'])) : date('Y-m-d', strtotime($nota['competencia'])) ?>">
                                                <div class="form-text">Data do início do contrato de locação (necessário para o DIMOB)</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dimob_enviado" class="form-label">Status DIMOB</label>
                                <select name="dimob_enviado" id="dimob_enviado" class="form-select">
                                    <option value="0" <?= (isset($nota['dimob_enviado']) && $nota['dimob_enviado'] == 0) ? 'selected' : '' ?>>Pendente de Envio</option>
                                    <option value="1" <?= (isset($nota['dimob_enviado']) && $nota['dimob_enviado'] == 1) ? 'selected' : '' ?>>Já Enviado</option>
                                </select>
                                <div class="form-text">Indica se esta nota já foi incluída em uma declaração DIMOB anterior</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="<?= site_url('dimob/listar') . '?ano=' . date('Y', strtotime($nota['competencia'])) . '&prestador_id=' . $nota['prestador_id']; ?>" class="btn btn-secondary me-2">
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
        const inquilinoDocumento = document.getElementById('inquilino_cpf_cnpj');
        const inquilinoEmail = document.getElementById('inquilino_email');
        
        function updateInquilinoFields() {
            if (inquilinoSelect.value === '') {
                // Limpar os campos quando nenhum inquilino estiver selecionado
                inquilinoNome.value = '';
                inquilinoDocumento.value = '';
                inquilinoEmail.value = '';
            } else {
                // Preencher com os dados do inquilino selecionado
                const selectedOption = inquilinoSelect.options[inquilinoSelect.selectedIndex];
                inquilinoNome.value = selectedOption.getAttribute('data-nome') || '';
                inquilinoDocumento.value = selectedOption.getAttribute('data-documento') || '';
                inquilinoEmail.value = selectedOption.getAttribute('data-email') || '';
            }
        }
        
        // Inicializar campos com base nas seleções atuais
        updateInquilinoFields();
        
        // Adicionar eventos de mudança
        inquilinoSelect.addEventListener('change', updateInquilinoFields);
        
        // Formatação CPF/CNPJ
        function formatCpfCnpj(value) {
            // Remover todos os caracteres não numéricos
            const numericValue = value.replace(/\D/g, '');
            
            // Verificar se é CPF ou CNPJ pelo tamanho
            if (numericValue.length <= 11) {
                // Format as CPF: 000.000.000-00
                if (numericValue.length <= 3) {
                    return numericValue;
                } else if (numericValue.length <= 6) {
                    return numericValue.replace(/(\d{3})(\d+)/, '$1.$2');
                } else if (numericValue.length <= 9) {
                    return numericValue.replace(/(\d{3})(\d{3})(\d+)/, '$1.$2.$3');
                } else {
                    return numericValue.replace(/(\d{3})(\d{3})(\d{3})(\d+)/, '$1.$2.$3-$4');
                }
            } else {
                // Format as CNPJ: 00.000.000/0000-00
                if (numericValue.length <= 2) {
                    return numericValue;
                } else if (numericValue.length <= 5) {
                    return numericValue.replace(/(\d{2})(\d+)/, '$1.$2');
                } else if (numericValue.length <= 8) {
                    return numericValue.replace(/(\d{2})(\d{3})(\d+)/, '$1.$2.$3');
                } else if (numericValue.length <= 12) {
                    return numericValue.replace(/(\d{2})(\d{3})(\d{3})(\d+)/, '$1.$2.$3/$4');
                } else {
                    return numericValue.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d+)/, '$1.$2.$3/$4-$5');
                }
            }
        }
        
        // Aplicar formatação aos campos CPF/CNPJ
        document.getElementById('inquilino_cpf_cnpj').addEventListener('input', function(e) {
            const cursor = this.selectionStart;
            const value = this.value;
            const newValue = formatCpfCnpj(value);
            
            if (value !== newValue) {
                this.value = newValue;
                // Manter a posição do cursor após a formatação
                this.setSelectionRange(cursor, cursor);
            }
        });
        
        // Confirmar antes de sair da página se houver mudanças
        let formChanged = false;
        
        document.querySelectorAll('form input, form select, form textarea').forEach(function(element) {
            element.addEventListener('change', function() {
                formChanged = true;
            });
        });
        
        window.addEventListener('beforeunload', function(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = 'Existem alterações não salvas. Deseja realmente sair da página?';
                return e.returnValue;
            }
        });
        
        document.querySelector('form').addEventListener('submit', function() {
            formChanged = false;
        });
    });
</script>
