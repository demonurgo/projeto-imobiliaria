<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-stethoscope"></i> Diagnóstico da Importação XML</h5>
                </div>
                <div class="card-body">
                    <h3>Resultados do Diagnóstico</h3>
                    <p>Esta página verifica a configuração do sistema para a importação de XML.</p>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th width="250">Item</th>
                                    <th>Status</th>
                                    <th>Detalhes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Diretório de Upload</td>
                                    <td>
                                        <?php if($upload_dir_exists): ?>
                                            <span class="badge bg-success">OK</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Falha</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong>Caminho:</strong> /uploads/xml/<br>
                                        <strong>Existe:</strong> <?= $upload_dir_exists ? 'Sim' : 'Não' ?><br>
                                        <?php if(!$upload_dir_exists && isset($dir_creation_attempt)): ?>
                                            <strong>Tentativa de criação:</strong> <?= $dir_creation_attempt ?><br>
                                        <?php endif; ?>
                                        <?php if($upload_dir_exists): ?>
                                            <strong>Permissão de escrita:</strong> <?= $upload_dir_writable ? 'Sim' : 'Não' ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Configurações PHP</td>
                                    <td>
                                        <span class="badge bg-info">Info</span>
                                    </td>
                                    <td>
                                        <strong>Tamanho máximo de upload:</strong> <?= $max_upload_size ?><br>
                                        <strong>Tamanho máximo de POST:</strong> <?= $max_post_size ?><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td>SimpleXML</td>
                                    <td>
                                        <?php if($simplexml_loaded): ?>
                                            <span class="badge bg-success">OK</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Falha</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong>Extensão carregada:</strong> <?= $simplexml_loaded ? 'Sim' : 'Não' ?><br>
                                        <?php if(!$simplexml_loaded): ?>
                                            <div class="alert alert-warning mt-2">
                                                A extensão SimpleXML é necessária para processar arquivos XML. Contate o administrador do servidor para habilitá-la.
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Teste de expressão regular</td>
                                    <td>
                                        <?php if($regex_test == 'Sucesso'): ?>
                                            <span class="badge bg-success">OK</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Falha</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong>Resultado:</strong> <?= $regex_test ?><br>
                                        <strong>Padrão testado:</strong> <code>/#(\d{11,14})#([^#]+)#([0-9.,]+)#/i</code><br>
                                        <strong>String de teste:</strong> <code>#03100742431#José dos Santos#1500,50# IMÓVEL - TESTE...</code><br>
                                        <pre style="max-height: 100px; overflow-y: auto;"><?= $regex_matches ?></pre>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('importacao') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar para Importação
                        </a>
                        <a href="<?= base_url('importacao/diagnostico') ?>" class="btn btn-primary">
                            <i class="fas fa-sync-alt"></i> Atualizar Diagnóstico
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-wrench"></i> Solução de Problemas</h5>
                </div>
                <div class="card-body">
                    <h5>Problemas comuns e soluções:</h5>
                    
                    <div class="accordion" id="troubleshootingAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    O diretório de upload não existe ou não tem permissão de escrita
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#troubleshootingAccordion">
                                <div class="accordion-body">
                                    <p>Verifique se o diretório <code>/uploads/xml/</code> existe e tem permissões de escrita (chmod 777).</p>
                                    <p>Você pode criar o diretório manualmente:</p>
                                    <ol>
                                        <li>Navegue até a pasta raiz do projeto</li>
                                        <li>Crie a pasta "uploads" se não existir</li>
                                        <li>Dentro da pasta "uploads", crie a pasta "xml"</li>
                                        <li>Defina as permissões para 777 (leitura, escrita e execução para todos)</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Erro ao fazer upload de arquivos
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#troubleshootingAccordion">
                                <div class="accordion-body">
                                    <p>Se os arquivos não estão sendo enviados corretamente, verifique:</p>
                                    <ul>
                                        <li>O tamanho do arquivo não excede o limite configurado (5MB)</li>
                                        <li>O arquivo é um XML válido</li>
                                        <li>As configurações do PHP (php.ini) permitem uploads de arquivos</li>
                                        <li>A extensão da arquivo é .xml</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Erro ao processar o XML
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#troubleshootingAccordion">
                                <div class="accordion-body">
                                    <p>Se o XML foi enviado mas não é processado corretamente:</p>
                                    <ul>
                                        <li>Verifique se a extensão SimpleXML está habilitada</li>
                                        <li>Confirme se o XML segue o formato ABRASF para NFS-e</li>
                                        <li>Verifique se o XML não está corrompido</li>
                                        <li>Teste com um arquivo XML de exemplo conhecido e válido</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
