<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Visualização do DIMOB</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Prévia do Arquivo DIMOB - <?php echo $prestador->razao_social; ?> (<?php echo $ano; ?>)</h6>
            <div class="dropdown no-arrow">
                <a class="btn btn-primary btn-sm" href="<?php echo base_url('dimob/generate'); ?>" role="button" id="generateDimob">
                    <i class="fas fa-file-export fa-sm"></i> Gerar Arquivo
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Esta é uma visualização prévia do arquivo DIMOB que será gerado. Verifique todos os dados antes de gerar o arquivo final.
            </div>
            
            <div class="table-responsive">
                <pre style="font-family: 'Courier New', Courier, monospace; font-size: 12px; white-space: pre-wrap; background-color: #f8f9fc; padding: 20px; border: 1px solid #e3e6f0; border-radius: 4px; height: 500px; overflow: auto;"><?php echo htmlspecialchars($content); ?></pre>
            </div>
            
            <div class="mt-3">
                <h5>Validação de Estrutura</h5>
                <?php
                // Verifica a estrutura básica do arquivo
                $lines = explode(PHP_EOL, trim($content));
                $validStructure = true;
                $errors = [];
                
                // Verifica se começa com DIMOB
                if (!isset($lines[0]) || substr($lines[0], 0, 5) !== 'DIMOB') {
                    $validStructure = false;
                    $errors[] = 'O header do arquivo deve começar com "DIMOB".';
                }
                
                // Verifica se há pelo menos um registro R01
                $hasR01 = false;
                foreach ($lines as $line) {
                    if (substr($line, 0, 3) === 'R01') {
                        $hasR01 = true;
                        break;
                    }
                }
                
                if (!$hasR01) {
                    $validStructure = false;
                    $errors[] = 'Arquivo deve conter pelo menos um registro R01 (Dados Iniciais).';
                }
                
                // Verifica se termina com T9
                $lastLine = end($lines);
                if (substr($lastLine, 0, 2) !== 'T9') {
                    $validStructure = false;
                    $errors[] = 'O trailer do arquivo deve ser "T9".';
                }
                
                if ($validStructure) {
                    echo '<div class="alert alert-success"><i class="fas fa-check-circle"></i> A estrutura básica do arquivo está correta.</div>';
                } else {
                    echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Problemas encontrados na estrutura do arquivo:</div>';
                    echo '<ul>';
                    foreach ($errors as $error) {
                        echo '<li>' . $error . '</li>';
                    }
                    echo '</ul>';
                }
                ?>
            </div>
            
            <div class="form-group text-center mt-4">
                <form action="<?php echo base_url('dimob/generate'); ?>" method="post">
                    <input type="hidden" name="prestador_id" value="<?php echo $prestador->id; ?>">
                    <input type="hidden" name="ano" value="<?php echo $ano; ?>">
                    <button type="submit" class="btn btn-primary btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-file-export"></i>
                        </span>
                        <span class="text">Gerar Arquivo DIMOB</span>
                    </button>
                    <a href="<?php echo base_url('dimob'); ?>" class="btn btn-secondary btn-icon-split ml-2">
                        <span class="icon text-white-50">
                            <i class="fas fa-arrow-left"></i>
                        </span>
                        <span class="text">Voltar</span>
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
