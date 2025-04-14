											<?php 
											$cpf_cnpj_duplicado = false;
											if (isset($nota['tomador_cpf_cnpj']) && isset($nota['inquilino_cpf_cnpj']) && 
												!empty($nota['tomador_cpf_cnpj']) && !empty($nota['inquilino_cpf_cnpj']) && 
												$nota['tomador_cpf_cnpj'] === $nota['inquilino_cpf_cnpj']) {
												$cpf_cnpj_duplicado = true;
											}
											?>
											<span <?= $cpf_cnpj_duplicado ? 'class="text-danger fw-bold" data-bs-toggle="tooltip" data-bs-placement="top" title="ATENÇÃO: O CPF/CNPJ do inquilino é igual ao do proprietário! Isso pode indicar um erro de cadastro."' : '' ?>>
												<?= $nota['inquilino_cpf_cnpj'] ?>
												<?php if($cpf_cnpj_duplicado): ?>
													<i class="fas fa-exclamation-triangle text-danger"></i>
												<?php endif; ?>
											</span>