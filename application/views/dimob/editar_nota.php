<div class="container-fluid">
    <!-- Breadcrumb de navegação -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo site_url('dashboard'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo site_url('dimob'); ?>">DIMOB</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar Nota #<?= $nota['numero'] ?></li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-edit mr-2"></i> Editar Nota Fiscal #<?= $nota['numero'] ?> para DIMOB</h5>
                    <div class="card-tools">
                        <a href="<?php echo site_url('dimob/listar') . '?ano=' . date('Y', strtotime($nota['competencia'])) . '&prestador_id=' . $nota['prestador_id']; ?>" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Voltar para Lista
                        </a>
                    </div>
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
                    
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info-circle mr-2"></i> Informações Importantes</h5>
                        <p class="mb-0">As alterações realizadas nesta página serão utilizadas para a geração do arquivo DIMOB. Os dados são essenciais para a correta declaração à Receita Federal.</p>
                        <p class="mt-1 mb-0"><strong>Nota:</strong> Ao selecionar um tomador, inquilino ou imóvel nos campos de seleção, os campos relacionados serão preenchidos automaticamente. Você também pode editar manualmente esses campos conforme necessário.</p>
                    </div>
                    
                    <?php echo form_open('dimob/atualizar_nota', ['class' => 'needs-validation dimob-edit-form', 'novalidate' => '']); ?>
                        <input type="hidden" name="id" value="<?php echo $nota['id']; ?>">
                        <input type="hidden" name="ano" value="<?php echo date('Y', strtotime($nota['competencia'])); ?>">
                        
                        <!-- Informações Básicas da Nota -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h4 class="border-bottom pb-2 text-primary"><i class="fas fa-file-invoice mr-2"></i>Informações Básicas da Nota Fiscal</h4>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">Número da Nota:</label>
                                    <input type="text" class="form-control" name="numero" value="<?php echo $nota['numero']; ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">Código de Verificação:</label>
                                    <input type="text" class="form-control" name="codigo_verificacao" value="<?php echo $nota['codigo_verificacao']; ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">Data de Emissão:</label>
                                    <input type="date" class="form-control" name="data_emissao" value="<?php echo date('Y-m-d', strtotime($nota['data_emissao'])); ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">Competência:</label>
                                    <input type="month" class="form-control" name="competencia" value="<?php echo date('Y-m', strtotime($nota['competencia'])); ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="prestador_id" class="form-label font-weight-bold">Prestador de Serviço</label>
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
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="valor_servicos" class="form-label">Valor do Serviço (R$):</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">R$</span>
                                        </div>
                                        <input type="text" name="valor_servicos" id="valor_servicos" class="form-control money" 
                                            value="<?php echo number_format($nota['valor_servicos'], 2, ',', '.'); ?>" 
                                            data-valor-original="<?php echo $nota['valor_servicos']; ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="valor_aluguel" class="form-label">Valor do Aluguel (R$):</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">R$</span>
                                        </div>
                                        <input type="text" name="valor_aluguel" id="valor_aluguel" class="form-control money" 
                                            value="<?php echo number_format($nota['valor_aluguel'] ?? 0, 2, ',', '.'); ?>" 
                                            data-valor-original="<?php echo $nota['valor_aluguel'] ?? 0; ?>" required>
                                    </div>
                                    <small class="text-muted">Valor mensal do aluguel do imóvel</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dados do Tomador (Proprietário) -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h4 class="border-bottom pb-2 text-primary"><i class="fas fa-user mr-2"></i>Dados do Tomador (Proprietário)</h4>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="tomador_nome" class="form-label font-weight-bold">Nome do Tomador:</label>
                                    <input type="text" class="form-control" id="tomador_nome" name="tomador_nome" value="<?php echo isset($tomador['razao_social']) ? $tomador['razao_social'] : ''; ?>">
                                    <input type="hidden" id="tomador_id" name="tomador_id" value="<?php echo $nota['tomador_id']; ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tomador_cpf_cnpj" class="form-label font-weight-bold">CPF/CNPJ do Tomador:</label>
                                    <input type="text" class="form-control" id="tomador_cpf_cnpj" name="tomador_cpf_cnpj" value="<?php echo isset($nota['tomador_cpf_cnpj']) ? $nota['tomador_cpf_cnpj'] : ($tomador['cpf_cnpj'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dados do Inquilino -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h4 class="border-bottom pb-2 text-primary"><i class="fas fa-user-friends mr-2"></i>Dados do Inquilino (Locatário)</h4>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="inquilino_nome" class="form-label font-weight-bold">Nome do Inquilino:</label>
                                    <input type="text" class="form-control" id="inquilino_nome" name="inquilino_nome" value="<?php echo isset($inquilino['nome']) ? $inquilino['nome'] : ''; ?>">
                                    <input type="hidden" id="inquilino_id" name="inquilino_id" value="<?php echo $nota['inquilino_id']; ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="inquilino_cpf_cnpj" class="form-label font-weight-bold">CPF/CNPJ do Inquilino:</label>
                                    <input type="text" class="form-control" id="inquilino_cpf_cnpj" name="inquilino_cpf_cnpj" value="<?php echo isset($nota['inquilino_cpf_cnpj']) ? $nota['inquilino_cpf_cnpj'] : ($inquilino['cpf_cnpj'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="inquilino_email" class="form-label font-weight-bold">E-mail:</label>
                                    <input type="text" class="form-control" id="inquilino_email" name="inquilino_email" value="<?php echo $inquilino['email'] ?? ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dados do Imóvel -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h4 class="border-bottom pb-2 text-primary"><i class="fas fa-home mr-2"></i>Dados do Imóvel</h4>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">Imóvel:</label>
                                    <input type="hidden" name="imovel_id" id="imovel_id" value="<?php echo $nota['imovel_id']; ?>" required
                                          data-valor-aluguel="<?php echo isset($imovel_atual['valor_aluguel']) ? $imovel_atual['valor_aluguel'] : 0; ?>"
                                          data-tipo="<?php echo isset($imovel_atual['tipo_imovel']) ? $imovel_atual['tipo_imovel'] : 'urbano'; ?>">
                                    <?php 
                                    $imovel_atual = null;
                                    foreach($imoveis as $imovel) {
                                        if($imovel['id'] == $nota['imovel_id']) {
                                            $imovel_atual = $imovel;
                                            break;
                                        }
                                    }
                                    
                                    $endereco_completo = '';
                                    if($imovel_atual) {
                                        $endereco_completo = $imovel_atual['endereco'];
                                        if(!empty($imovel_atual['numero'])) $endereco_completo .= ', ' . $imovel_atual['numero'];
                                        if(!empty($imovel_atual['bairro'])) $endereco_completo .= ' - ' . $imovel_atual['bairro'];
                                        if(!empty($imovel_atual['cidade'])) $endereco_completo .= ' - ' . $imovel_atual['cidade'] . '/' . $imovel_atual['uf'];
                                    }
                                    ?>
                                    <div class="form-control bg-light"><?php echo $endereco_completo; ?></div>
                                    <small class="text-muted">Imóvel associado a esta nota fiscal. Para alterar o imóvel, edite o registro da nota no módulo principal.</small>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="imovel_tipo" class="form-label">Tipo do Imóvel:</label>
                                    <select name="tipo_imovel" id="imovel_tipo" class="form-select">
                                        <option value="urbano" <?php echo (isset($nota['tipo_imovel']) && $nota['tipo_imovel'] == 'urbano') ? 'selected' : ''; ?>>Urbano</option>
                                        <option value="rural" <?php echo (isset($nota['tipo_imovel']) && $nota['tipo_imovel'] == 'rural') ? 'selected' : ''; ?>>Rural</option>
                                    </select>
                                    <small class="text-muted">Esta informação é obrigatória para o DIMOB</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informações do Serviço -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h4 class="border-bottom pb-2 text-primary"><i class="fas fa-clipboard-list mr-2"></i>Informações do Serviço</h4>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="discriminacao" class="form-label">Discriminação do Serviço:</label>
                                    <textarea class="form-control" id="discriminacao" name="discriminacao" rows="3"><?php echo $nota['discriminacao']; ?></textarea>
                                    <small class="text-muted">Descrição completa do serviço conforme consta na nota fiscal</small>
                                </div>
                            </div>
                            
                            
                        </div>
                        
                        <!-- Informações para DIMOB -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h4 class="border-bottom pb-2 text-primary"><i class="fas fa-file-alt mr-2"></i>Informações para DIMOB</h4>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dimob_enviado" class="form-label">Status DIMOB:</label>
                                    <select name="dimob_enviado" id="dimob_enviado" class="form-select">
                                        <option value="0" <?php echo (isset($nota['dimob_enviado']) && $nota['dimob_enviado'] == 0) ? 'selected' : ''; ?>>Pendente de Envio</option>
                                        <option value="1" <?php echo (isset($nota['dimob_enviado']) && $nota['dimob_enviado'] == 1) ? 'selected' : ''; ?>>Já Enviado</option>
                                    </select>
                                    <small class="text-muted">Indica se esta nota já foi incluída em uma declaração DIMOB anterior</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="data_inicio_locacao" class="form-label">Data de Início da Locação:</label>
                                    <input type="date" class="form-control" id="data_inicio_locacao" name="data_inicio_locacao" value="<?php echo isset($nota['data_inicio_locacao']) ? date('Y-m-d', strtotime($nota['data_inicio_locacao'])) : date('Y-m-d', strtotime($nota['competencia'])); ?>">
                                    <small class="text-muted">Data do início do contrato de locação (necessário para o DIMOB)</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle mr-2"></i> <strong>Atenção:</strong> Verifique cuidadosamente todas as informações acima antes de salvar, pois elas são essenciais para a correta geração do arquivo DIMOB.
                                </div>
                            </div>
                            
                            <div class="col-12 d-flex justify-content-between">
                                <a href="<?php echo site_url('dimob/listar') . '?ano=' . date('Y', strtotime($nota['competencia'])) . '&prestador_id=' . $nota['prestador_id']; ?>" class="btn btn-secondary">
                                    <i class="fas fa-times mr-2"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-save mr-2"></i> Salvar Alterações
                                </button>
                            </div>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Script para melhorar a usabilidade do formulário de edição
$(document).ready(function() {
    console.log('Script de inicialização do formulário de edição DIMOB executado');
    
// Função para atualizar os campos do tomador
    $('#tomador_id').on('change', function() {
        var selectElement = $(this);
        var selectedOption = $('option:selected', selectElement);
        
        // Limpar campos antigos
        $('#tomador_nome').val('');
        $('#tomador_cpf_cnpj').val('');
        
        if (selectedOption.val()) {
            // Usar data-attributes diretamente para evitar parsing
            var cpfCnpj = selectedOption.data('cpf-cnpj') || '';
            var nome = selectedOption.data('nome') || '';
            
            // Preencher os campos
            $('#tomador_cpf_cnpj').val(cpfCnpj);
            $('#tomador_nome').val(nome);
            
            console.log("Tomador selecionado: ID=", selectedOption.val(), 
                      "Nome=", nome, 
                      "CPF/CNPJ=", cpfCnpj);
        }
    });
    
// Função para atualizar os campos do inquilino
    $('#inquilino_id').on('change', function() {
        var selectElement = $(this);
        var selectedOption = $('option:selected', selectElement);
        
        // Limpar campos antigos
        $('#inquilino_nome').val('');
        $('#inquilino_cpf_cnpj').val('');
        $('#inquilino_email').val('');
        
        if (selectedOption.val()) {
            // Usar data-attributes diretamente para evitar parsing
            var cpfCnpj = selectedOption.data('cpf-cnpj') || '';
            var nome = selectedOption.data('nome') || '';
            var email = selectedOption.data('email') || '';
            
            // Preencher os campos
            $('#inquilino_cpf_cnpj').val(cpfCnpj);
            $('#inquilino_nome').val(nome);
            $('#inquilino_email').val(email);
            
            console.log("Inquilino selecionado: ID=", selectedOption.val(), 
                      "Nome=", nome, 
                      "CPF/CNPJ=", cpfCnpj);
        }
    });
    
    // Função para configurar o valor do aluguel a partir do imóvel selecionado
    function configurarDadosImovel() {
        var imovelId = $('#imovel_id').val();
        if (!imovelId) return;
        
        // Como não temos mais o select, obtemos os dados do imóvel
        // diretamente da variável PHP que recuperamos anteriormente
        var valorAluguel = "<?php echo isset($imovel_atual['valor_aluguel']) ? $imovel_atual['valor_aluguel'] : '0.00'; ?>";
        var tipo = "<?php echo isset($imovel_atual['tipo_imovel']) ? $imovel_atual['tipo_imovel'] : 'urbano'; ?>";
        
        // Formatar o valor do aluguel
        if (valorAluguel) {
            var valorFormatado = parseFloat(valorAluguel).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).replace('.', ',');
            
            $('#valor_aluguel').val(valorFormatado);
        }
        
        // Definir o tipo do imóvel
        if (tipo) {
            $('#imovel_tipo').val(tipo);
        }
        
        console.log("Dados do imóvel configurados. Valor:", valorAluguel, "Tipo:", tipo);
    }
    
    // Adicionar eventos de submit ao formulário para garantir dados consistentes
    $('form').on('submit', function(e) {
        // Impedir o envio default do formulário
        e.preventDefault();
        
        console.log('Formulário sendo enviado, processando valores...');
        
        // Obter os valores originais dos campos monetários (sem máscara)
        var valorAluguelOriginal = $('#valor_aluguel').data('valor-original') || $('#valor_aluguel').val();
        var valorServicosOriginal = $('#valor_servicos').data('valor-original') || $('#valor_servicos').val();
        
        console.log('Valor aluguel original:', valorAluguelOriginal);
        console.log('Valor serviços original:', valorServicosOriginal);
        
        // Se não foram alterados pelo usuário, usar os valores originais
        if (!formChanged) {
            $('#valor_aluguel').val(valorAluguelOriginal);
            $('#valor_servicos').val(valorServicosOriginal);
            console.log('Usando valores originais pois não houve alteração');
        } else {
            // Se foram alterados, remover a máscara e formatar corretamente
            // Remover a máscara dos campos monetários
            $('.money').each(function() {
                var valor = $(this).val();
                // Se o valor contiver vírgula, é formato brasileiro
                if (valor && valor.includes(',')) {
                    // Remover pontos e substituir vírgula por ponto
                    valor = valor.replace(/\./g, '').replace(',', '.');
                    $(this).val(valor);
                    console.log('Valor formatado:', $(this).attr('id'), valor);
                }
            });
        }
        
        // Garantir que os campos de data estão no formato correto
        var dataEmissao = $('#data_emissao').val();
        if (dataEmissao && !dataEmissao.includes('T')) {
            $('#data_emissao').val(dataEmissao + 'T00:00:00');
        }
        
        // Enviar o formulário
        this.submit();
    });
    
    // Adicionar formatação CPF/CNPJ para tomador
    $('#tomador_cpf_cnpj').on('input', function() {
        var valor = $(this).val().replace(/\D/g, '');
        
        if (valor.length > 11) {
            // CNPJ
            $(this).unmask(); // Remove máscara anterior, se houver
            $(this).mask('00.000.000/0000-00');
        } else {
            // CPF
            $(this).unmask(); // Remove máscara anterior, se houver
            $(this).mask('000.000.000-00');
        }
    });
    // Trigger para iniciar a formatação
    $('#tomador_cpf_cnpj').trigger('input');
    
    // Adicionar formatação CPF/CNPJ para inquilino
    $('#inquilino_cpf_cnpj').on('input', function() {
        var valor = $(this).val().replace(/\D/g, '');
        
        if (valor.length > 11) {
            // CNPJ
            $(this).unmask(); // Remove máscara anterior, se houver
            $(this).mask('00.000.000/0000-00');
        } else {
            // CPF
            $(this).unmask(); // Remove máscara anterior, se houver
            $(this).mask('000.000.000-00');
        }
    });
    // Trigger para iniciar a formatação
    $('#inquilino_cpf_cnpj').trigger('input');
    
    // Formatação do telefone
    $('#inquilino_telefone').mask('(00) 00000-0000');
    
    // Formatação para campos monetários
    $('.money').mask('#.##0,00', {reverse: true, clearIfNotMatch: true});
    
    // Função para inicializar os campos no carregamento da página
    function inicializarFormulario() {
        console.log('Inicializando formulário...');
        
        // Verificar se há valores iniciais
        var tomadorNome = '<?php echo isset($nota["tomador_nome"]) ? addslashes($nota["tomador_nome"]) : ""; ?>';
        var tomadorCPF = '<?php echo isset($nota["tomador_cpf_cnpj"]) ? $nota["tomador_cpf_cnpj"] : ""; ?>';
        var inquilinoNome = '<?php echo isset($nota["inquilino_nome"]) ? addslashes($nota["inquilino_nome"]) : ""; ?>';
        var inquilinoCPF = '<?php echo isset($nota["inquilino_cpf_cnpj"]) ? $nota["inquilino_cpf_cnpj"] : ""; ?>';
        
        console.log('Valores pré-definidos do banco:', {
            tomadorNome, tomadorCPF, inquilinoNome, inquilinoCPF
        });
        
        // Garantir que os seletores estejam definidos e dispare eventos
        if ($('#tomador_id').val()) {
            $('#tomador_id').trigger('change');
        } else {
            $('#tomador_nome').val(tomadorNome);
            $('#tomador_cpf_cnpj').val(tomadorCPF);
        }
        
        if ($('#inquilino_id').val()) {
            $('#inquilino_id').trigger('change');
        } else {
            $('#inquilino_nome').val(inquilinoNome);
            $('#inquilino_cpf_cnpj').val(inquilinoCPF);
        }
        
        if ($('#imovel_id').val()) {
            $('#imovel_id').trigger('change');
        }
        
        // Formatar campos de CPF/CNPJ
        formatarCPFCNPJ();
    }
    
    // Função para formatar os campos de CPF/CNPJ
    function formatarCPFCNPJ() {
        $('#tomador_cpf_cnpj, #inquilino_cpf_cnpj').each(function() {
            var valor = $(this).val().replace(/\D/g, '');
            if (valor.length > 0) {
                $(this).trigger('input');
            }
        });
    }
    
    // Aguardar o DOM estar pronto e inicializar o formulário
    $(document).ready(function() {
        console.log('DOM pronto, inicializando formulário...');
        setTimeout(inicializarFormulario, 300);
        
        // Inicializamos o valor do aluguel do imóvel
        configurarDadosImovel();
        
        // Confirmação antes de sair da página se houver mudanças
        let formChanged = false;
        
        // Monitorar mudanças em todos os campos do formulário
        $('form input, form select, form textarea').on('change', function() {
            formChanged = true;
            console.log('Formulário modificado pelo usuário');
        });
        
        // Monitorar mudanças específicas nos campos monetários
        $('.money').on('input', function() {
            var currentVal = $(this).val().replace(/\D/g, '');
            var originalVal = String($(this).data('valor-original')).replace(/\D/g, '');
            
            // Se o valor for diferente do original (ignorando formatação)
            if (currentVal !== originalVal) {
                formChanged = true;
                console.log('Valor monetário modificado:', $(this).attr('id'));
            }
        });
        
        $('form').on('submit', function() {
            formChanged = false;
        });
        
        $(window).on('beforeunload', function() {
            if (formChanged) {
                return "Existem alterações não salvas. Deseja realmente sair da página?";
            }
        });
    });
});
</script>

<style>
    /* Estilos personalizados para a página de edição de notas DIMOB */
    .card-header.bg-primary {
        border-bottom: 0;
    }
    
    .form-label {
        margin-bottom: 0.5rem;
        color: #495057;
    }
    
    .font-weight-bold {
        font-weight: 600;
    }
    
    /* Estilos para os breadcrumbs */
    .breadcrumb {
        background-color: #f8f9fa;
        border-radius: 0.25rem;
        padding: 0.75rem 1rem;
    }
    
    /* Melhorias para campos do formulário */
    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    /* Melhorias para selects */
    select.form-control {
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
    }
    
    /* Estilos para botões */
    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 1.1rem;
    }
</style>

<!-- O JavaScript está incorporado no script.js global -->
