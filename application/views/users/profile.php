<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-id-card"></i> Meu Perfil</h5>
                </div>
                <div class="card-body">
                    <?php echo form_open('users/profile'); ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Nome de Usuário</label>
                                <input type="text" class="form-control" id="username" value="<?php echo $user->username; ?>" readonly disabled>
                                <small class="text-muted">O nome de usuário não pode ser alterado.</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control <?php echo form_error('name') ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo set_value('name', $user->name); ?>" required>
                                <div class="invalid-feedback"><?php echo form_error('name'); ?></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control <?php echo form_error('email') ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo set_value('email', $user->email); ?>" required>
                                <div class="invalid-feedback"><?php echo form_error('email'); ?></div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">Informações da Conta</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-1"><strong>Tipo de Conta:</strong> 
                                            <?php if ($user->is_admin == 1): ?>
                                                <span class="badge bg-danger">Administrador</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Usuário</span>
                                            <?php endif; ?>
                                        </p>
                                        <p class="mb-1"><strong>Último Login:</strong> 
                                            <?php 
                                                if ($user->last_login) {
                                                    echo date('d/m/Y H:i', strtotime($user->last_login));
                                                } else {
                                                    echo '<span class="text-muted">Nunca</span>';
                                                }
                                            ?>
                                        </p>
                                        <p class="mb-0"><strong>Criado em:</strong> <?php echo date('d/m/Y', strtotime($user->created_at)); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="card border-warning">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0">Alterar Senha</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="current_password" class="form-label">Senha Atual</label>
                                                <input type="password" class="form-control <?php echo form_error('current_password') ? 'is-invalid' : ''; ?>" id="current_password" name="current_password">
                                                <div class="invalid-feedback"><?php echo form_error('current_password'); ?></div>
                                                <small class="text-muted">Necessário apenas se desejar alterar a senha</small>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="password" class="form-label">Nova Senha</label>
                                                <input type="password" class="form-control <?php echo form_error('password') ? 'is-invalid' : ''; ?>" id="password" name="password">
                                                <div class="invalid-feedback"><?php echo form_error('password'); ?></div>
                                                <small class="text-muted">Deixe em branco para manter a senha atual</small>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="confirm_password" class="form-label">Confirmar Nova Senha</label>
                                                <input type="password" class="form-control <?php echo form_error('confirm_password') ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password">
                                                <div class="invalid-feedback"><?php echo form_error('confirm_password'); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Salvar Alterações
                                </button>
                                <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                            </div>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
