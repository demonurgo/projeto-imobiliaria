<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-user-edit"></i> Editar Usuário: <?php echo $user->name; ?></h5>
                </div>
                <div class="card-body">
                    <?php echo form_open('users/edit/'.$user->id); ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Nome de Usuário</label>
                                <input type="text" class="form-control <?php echo form_error('username') ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?php echo set_value('username', $user->username); ?>" required>
                                <div class="invalid-feedback"><?php echo form_error('username'); ?></div>
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

                            <div class="col-md-3 mb-3">
                                <label for="password" class="form-label">Nova Senha</label>
                                <input type="password" class="form-control <?php echo form_error('password') ? 'is-invalid' : ''; ?>" id="password" name="password">
                                <div class="invalid-feedback"><?php echo form_error('password'); ?></div>
                                <small class="text-muted">Deixe em branco para manter a senha atual</small>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="confirm_password" class="form-label">Confirmar Nova Senha</label>
                                <input type="password" class="form-control <?php echo form_error('confirm_password') ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password">
                                <div class="invalid-feedback"><?php echo form_error('confirm_password'); ?></div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin" value="1" <?php echo $user->is_admin == 1 ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_admin">
                                        Este usuário é um administrador
                                    </label>
                                    <small class="d-block text-muted">Administradores têm acesso completo ao sistema, incluindo gerenciamento de usuários.</small>
                                </div>
                            </div>
                        </div>
                            
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Salvar Alterações
                                </button>
                                <a href="<?php echo base_url('users'); ?>" class="btn btn-secondary">
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
