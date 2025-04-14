<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-file-import"></i> Importação de XML</h5>
                    <a href="<?= base_url('importacao/diagnostico') ?>" class="btn btn-light btn-sm">
                        <i class="fas fa-stethoscope"></i>
                    </a>
                </div>
                <div class="card-body">
                    <h3>Upload de Arquivos XML de NFS-e</h3>
                    <p>Selecione um ou mais arquivos XML para importar notas fiscais para o sistema.</p>
                    
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
                    
                    <ul class="nav nav-tabs" id="importTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="single-tab" data-bs-toggle="tab" href="#single" role="tab" aria-controls="single" aria-selected="true">
                                <i class="fas fa-file"></i> Upload Individual
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="multiple-tab" data-bs-toggle="tab" href="#multiple" role="tab" aria-controls="multiple" aria-selected="false">
                                <i class="fas fa-file-medical-alt"></i> Upload Múltiplo
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content mt-3" id="importTabsContent">
                        <!-- Upload Individual -->
                        <div class="tab-pane fade show active" id="single" role="tabpanel" aria-labelledby="single-tab">
                            <?php echo form_open_multipart('importacao/upload', ['class' => 'needs-validation', 'novalidate' => '']); ?>
                                <input type="hidden" name="upload_type" value="single">
                                
                                <div class="form-group mb-3">
                                    <label for="xmlfile" class="form-label">Selecione um arquivo XML:</label>
                                    <input type="file" class="form-control" id="xmlfile" name="xmlfile" accept=".xml" required>
                                    <div class="form-text">Arquivos XML de Notas Fiscais (NFS-e)</div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload"></i> Importar Arquivo
                                </button>
                            <?php echo form_close(); ?>
                        </div>
                        
                        <!-- Upload Múltiplo -->
                        <div class="tab-pane fade" id="multiple" role="tabpanel" aria-labelledby="multiple-tab">
                            <?php echo form_open_multipart('importacao/upload', ['class' => 'needs-validation', 'novalidate' => '']); ?>
                                <input type="hidden" name="upload_type" value="multiple">
                                
                                <div class="form-group mb-3">
                                    <label for="xmlfiles" class="form-label">Selecione os arquivos XML:</label>
                                    <input type="file" class="form-control" id="xmlfiles" name="xmlfiles[]" multiple accept=".xml" required>
                                    <div class="form-text">Você pode selecionar vários arquivos XML de uma vez</div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload"></i> Importar Arquivos
                                </button>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-info-circle"></i> Informações</h5>
                </div>
                <div class="card-body">
                    <h4>Formato das Notas Fiscais de Serviço</h4>
                    <p>O sistema está configurado para processar XMLs de Notas Fiscais de Serviço Eletrônicas (NFS-e) no formato ABRASF.</p>
                    
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-exclamation-triangle"></i> Campo "Discriminação"</h5>
                        <p>O sistema extrai informações especiais do campo "Discriminação" do XML. Para melhor identificação, utilize o seguinte formato:</p>
                        <pre class="bg-light p-3">
#CPF_INQUILINO#NOME_INQUILINO#VALOR_ALUGUEL# DESCRIÇÃO DO SERVIÇO
						</pre>
                        <p><strong>Exemplo:</strong></p>
                        <pre class="bg-light p-3">
#03100742431#José dos Santos#1580,20# IMOBILIÁRIA XYZ - SERVIÇO REFERENTE A TAXA DE ADMINISTRAÇÃO E CORRETAGEM DO IMÓVEL.

RUA DA CAPELA N 89 A - JARDIM SÃO PAULO</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Ativando todos os tooltips
    document.addEventListener('DOMContentLoaded', function() {
        // Habilitar validação do formulário
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
    });
</script>
