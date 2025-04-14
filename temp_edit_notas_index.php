<td>
    <?php if ($nota['inquilino_id'] && isset($nota['inquilino_cpf_cnpj'])): ?>
        <?php 
        $cpf_cnpj_duplicado = false;
        if (isset($nota['tomador_cpf_cnpj']) && isset($nota['inquilino_cpf_cnpj']) && 
            !empty($nota['tomador_cpf_cnpj']) && !empty($nota['inquilino_cpf_cnpj']) && 
            $nota['tomador_cpf_cnpj'] === $nota['inquilino_cpf_cnpj']) {
            $cpf_cnpj_duplicado = true;
        }
        ?>
        <span <?= $cpf_cnpj_duplicado ? 'class="text-danger fw-bold"' : '' ?>>
            <?= $nota['inquilino_cpf_cnpj'] ?>
            <?php if($cpf_cnpj_duplicado): ?>
                <i class="fas fa-exclamation-triangle text-danger" title="CPF/CNPJ do inquilino igual ao do proprietário!"></i>
            <?php endif; ?>
        </span>
    <?php else: ?>
        <span class="badge bg-warning text-dark">Não identificado</span>
    <?php endif; ?>
</td>