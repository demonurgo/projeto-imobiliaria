<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-file-invoice"></i> Detalhes da Nota Fiscal #<?= $nota['numero'] ?></h5>
                    <div>
                        <a href="<?= base_url('notas/edit/'.$nota['id']) ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="<?= base_url('notas') ?>" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                
                    <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Painel de Informações Gerais -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informações Gerais</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Número:</strong> <?= $nota['numero'] ?></p>
                                            <p><strong>Código de Verificação:</strong> <?= $nota['codigo_verificacao'] ?></p>
                                            <p><strong>Data de Emissão:</strong> <?= date('d/m/Y H:i', strtotime($nota['data_emissao'])) ?></p>
                                            <p><strong>Competência:</strong> <?= $nota['competencia'] ? date('m/Y', strtotime($nota['competencia'])) : 'Não informada' ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Status:</strong> 
                                                <?php 
                                                switch($nota['status']) {
                                                    case 'importado':
                                                        echo '<span class="badge bg-info">Importado</span>';
                                                        break;
                                                    case 'processado':
                                                        echo '<span class="badge bg-success">Processado</span>';
                                                        break;
                                                    case 'atualizado':
                                                        echo '<span class="badge bg-primary">Atualizado</span>';
                                                        break;
                                                    case 'cancelado':
                                                        echo '<span class="badge bg-danger">Cancelado</span>';
                                                        break;
                                                    default:
                                                        echo '<span class="badge bg-secondary">Desconhecido</span>';
                                                }
                                                ?>
                                            </p>
                                            <p><strong>DIMOB:</strong> 
                                                <?php if($nota['dimob_enviado']): ?>
                                                <span class="badge bg-success">Incluído</span>
                                                <?php else: ?>
                                                <span class="badge bg-secondary">Pendente</span>
                                                <?php endif; ?>
                                            </p>
                                            <p><strong>Editado Manualmente:</strong> 
                                                <?php if(isset($nota['editado_manualmente']) && $nota['editado_manualmente']): ?>
                                                <span class="badge bg-primary">Sim</span>
                                                <?php else: ?>
                                                <span class="badge bg-secondary">Não</span>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Seção de Valores -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-dollar-sign"></i> Informações Financeiras</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Valores da Nota Fiscal -->
                                        <div class="col-md-6">
                                            <h6 class="border-bottom pb-2 mb-3">Valores da Nota Fiscal</h6>
                                            <p><strong>Valor dos Serviços:</strong> <span class="text-primary">R$ <?= number_format($nota['valor_servicos'], 2, ',', '.') ?></span></p>
                                            <p><strong>Base de Cálculo:</strong> R$ <?= number_format($nota['base_calculo'], 2, ',', '.') ?></p>
                                            <p><strong>Alíquota:</strong> <?= number_format($nota['aliquota'] * 100, 2, ',', '.') ?>%</p>
                                            <p><strong>Valor do ISS:</strong> R$ <?= number_format($nota['valor_iss'], 2, ',', '.') ?></p>
                                            <p><strong>Valor Líquido:</strong> R$ <?= number_format($nota['valor_liquido'], 2, ',', '.') ?></p>
                                        </div>
                                        
                                        <!-- Valores do Imóvel -->
                                        <div class="col-md-6">
                                            <h6 class="border-bottom pb-2 mb-3">Valores do Imóvel</h6>
                                            <?php if(isset($nota['valor_aluguel']) && $nota['valor_aluguel']): ?>
                                                <p><strong>Valor do Aluguel:</strong> <span class="text-success">R$ <?= number_format($nota['valor_aluguel'], 2, ',', '.') ?></span></p>
                                                <?php 
                                                // Calcular percentual da administração (valor serviço / valor aluguel)
                                                if($nota['valor_aluguel'] > 0) {
                                                    $percentual = ($nota['valor_servicos'] / $nota['valor_aluguel']) * 100;
                                                    echo '<p><strong>Taxa de Administração:</strong> ' . number_format($percentual, 2, ',', '.') . '%</p>';
                                                }
                                                ?>
                                            <?php else: ?>
                                                <p><span class="badge bg-warning text-dark">Valor do aluguel não informado</span></p>
                                                <p>É necessário editar esta nota ou o imóvel para informar o valor do aluguel.</p>
                                            <?php endif; ?>
                                            
                                            <?php 
                                            // Tentar extrair valor do aluguel da discriminação
                                            if (preg_match('/#([^#]+)#([^#]+)#([^#]+)#/', $nota['discriminacao'], $matches) && !empty($matches[3])) {
                                                $valor_discriminacao = trim(str_replace(',', '.', str_replace('.', '', $matches[3])));
                                                if (is_numeric($valor_discriminacao) && $valor_discriminacao > 0) {
                                                    echo '<p><strong>Valor na Discriminação:</strong> R$ ' . number_format($valor_discriminacao, 2, ',', '.') . '</p>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <!-- Imóvel Relacionado -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-building"></i> Imóvel Relacionado</h5>
                                </div>
                                <div class="card-body">
                                    <?php if(isset($nota['imovel_id']) && $nota['imovel_id']): ?>
                                        <p><strong>Endereço:</strong> <?= $nota['imovel_endereco'] ?></p>
                                        
                                        <?php if(isset($nota['tipo_imovel']) && $nota['tipo_imovel']): ?>
                                            <p><strong>Tipo de Imóvel:</strong> 
                                                <?= ($nota['tipo_imovel'] == 'urbano') ? 'Urbano' : 'Rural' ?>
                                            </p>
                                        <?php endif; ?>
                                        
                                        <div class="mt-3">
                                            <a href="<?= base_url('imoveis/view/' . $nota['imovel_id']) ?>" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-info-circle"></i> Ver Detalhes do Imóvel
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i> Nenhum imóvel associado a esta nota.
                                        </div>
                                        <p>Para gerar corretamente a DIMOB, é necessário associar um imóvel a esta nota fiscal.</p>
                                        <div class="mt-3">
                                            <a href="<?= base_url('notas/edit/' . $nota['id']) ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-link"></i> Associar Imóvel
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Status DIMOB -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-check-circle"></i> Status DIMOB</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Status:</strong> 
                                        <?php if($nota['dimob_enviado']): ?>
                                            <span class="badge bg-success">Incluído na DIMOB</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Pendente de Inclusão</span>
                                        <?php endif; ?>
                                    </p>
                                    
                                    <!-- Verificação de dados necessários para DIMOB -->
                                    <div class="mt-3">
                                        <h6>Checklist DIMOB:</h6>
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Imóvel Associado
                                                <?php if(isset($nota['imovel_id']) && $nota['imovel_id']): ?>
                                                    <span class="badge bg-success rounded-pill"><i class="fas fa-check"></i></span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger rounded-pill"><i class="fas fa-times"></i></span>
                                                <?php endif; ?>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Inquilino Identificado
                                                <?php if(isset($nota['inquilino_id']) && $nota['inquilino_id']): ?>
                                                    <span class="badge bg-success rounded-pill"><i class="fas fa-check"></i></span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger rounded-pill"><i class="fas fa-times"></i></span>
                                                <?php endif; ?>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Valor do Aluguel
                                                <?php if(isset($nota['valor_aluguel']) && $nota['valor_aluguel'] > 0): ?>
                                                    <span class="badge bg-success rounded-pill"><i class="fas fa-check"></i></span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger rounded-pill"><i class="fas fa-times"></i></span>
                                                <?php endif; ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tomador e Inquilino (lado a lado) -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-user"></i> Tomador de Serviço (Proprietário)</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Nome/Razão Social:</strong> <?= $nota['tomador_nome'] ?></p>
                                    <?php if(isset($nota['tomador_cpf_cnpj']) && $nota['tomador_cpf_cnpj']): ?>
                                        <p><strong>CPF/CNPJ:</strong> <?= $nota['tomador_cpf_cnpj'] ?></p>
                                    <?php endif; ?>
                                    
                                    <?php if(isset($nota['tomador_id'])): ?>
                                        <div class="mt-3">
                                            <a href="<?= base_url('tomadores/view/' . $nota['tomador_id']) ?>" class="btn btn-outline-info btn-sm">
                                                <i class="fas fa-info-circle"></i> Ver Detalhes do Tomador
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-user-friends"></i> Inquilino</h5>
                                </div>
                                <div class="card-body">
                                    <?php if(isset($nota['inquilino_id']) && $nota['inquilino_id']): ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Nome:</strong> <?= $nota['inquilino_nome'] ?></p>
                                                <?php if(isset($nota['inquilino_cpf_cnpj']) && $nota['inquilino_cpf_cnpj']): ?>
                                                    <p><strong>CPF/CNPJ:</strong> <?= $nota['inquilino_cpf_cnpj'] ?></p>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mt-3">
                                                    <a href="<?= base_url('inquilinos/view/' . $nota['inquilino_id']) ?>" class="btn btn-outline-info btn-sm">
                                                        <i class="fas fa-info-circle"></i> Ver Detalhes do Inquilino
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i> Nenhum inquilino associado a esta nota.
                                        </div>
                                        <p>Para gerar corretamente a DIMOB, é necessário associar um inquilino a esta nota fiscal.</p>
                                        
                                        <?php if(preg_match('/#([^#]+)#([^#]+)#/', $nota['discriminacao'], $matches) && !empty($matches[2])): ?>
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle"></i> <strong>Possível inquilino identificado na discriminação:</strong> <?= trim($matches[2]) ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="mt-3">
                                            <a href="<?= base_url('notas/edit/' . $nota['id']) ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-link"></i> Associar Inquilino
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Discriminação do Serviço -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-file-alt"></i> Discriminação do Serviço</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><strong>Descrição:</strong> <?= $nota['descricao_servico'] ?></p>
                                            <p><strong>Discriminação Original:</strong></p>
                                            <pre class="bg-light p-3 border rounded"><?= $nota['discriminacao'] ?></pre>
                                            
                                            <?php if(preg_match('/#([^#]+)#([^#]+)#([^#]+)#/', $nota['discriminacao'], $matches)): ?>
                                                <div class="alert alert-info mt-3">
                                                    <h6><i class="fas fa-info-circle"></i> Dados Extraídos da Discriminação:</h6>
                                                    <ul>
                                                        <?php if(!empty($matches[1])): ?>
                                                            <li><strong>CPF/CNPJ:</strong> <?= trim($matches[1]) ?></li>
                                                        <?php endif; ?>
                                                        <?php if(!empty($matches[2])): ?>
                                                            <li><strong>Nome Inquilino:</strong> <?= trim($matches[2]) ?></li>
                                                        <?php endif; ?>
                                                        <?php if(!empty($matches[3])): ?>
                                                            <li><strong>Valor:</strong> R$ <?= trim($matches[3]) ?></li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Prestador de Serviço -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-building"></i> Prestador de Serviço</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Nome/Razão Social:</strong> <?= $nota['prestador_nome'] ?></p>
                                            <?php if(isset($nota['prestador_id']) && isset($nota['prestador_cpf_cnpj']) && $nota['prestador_cpf_cnpj']): ?>
                                                <p><strong>CNPJ:</strong> <?= $nota['prestador_cpf_cnpj'] ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?php if(isset($nota['prestador_id'])): ?>
                                                <div class="mt-3">
                                                    <a href="<?= base_url('prestadores/view/' . $nota['prestador_id']) ?>" class="btn btn-outline-info btn-sm">
                                                        <i class="fas fa-info-circle"></i> Ver Detalhes do Prestador
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botões de Ação -->
                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('notas') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar para Lista
                        </a>
                        
                        <div>
                            <a href="<?= base_url('notas/edit/'.$nota['id']) ?>" class="btn btn-primary me-2">
                                <i class="fas fa-edit"></i> Editar Nota
                            </a>
                            
                            <?php if(!$nota['dimob_enviado']): ?>
                                <a href="<?= base_url('notas/dimob/'.$nota['id'].'/1') ?>" class="btn btn-success">
                                    <i class="fas fa-check"></i> Marcar para DIMOB
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('notas/dimob/'.$nota['id'].'/0') ?>" class="btn btn-warning">
                                    <i class="fas fa-times"></i> Remover da DIMOB
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
