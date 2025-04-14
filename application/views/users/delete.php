<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-exclamation-triangle"></i> Confirmar Exclusão</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle"></i> Atenção! Esta ação não pode ser desfeita.
                    </div>
                    
                    <h4>Você está prestes a excluir o usuário:</h4>
                    
                    <div class="row mb-4 mt-4">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="150">ID:</th>
                                    <td><?php echo $user->id; ?></td>
                                </tr>
                                <tr>
                                    <th>Usuário:</th>
                                    <td><?php echo $user->username; ?></td>
                                </tr>
                                <tr>
                                    <th>Nome:</th>
                                    <td><?php echo $user->name; ?></td>
                                </tr>
                                <tr>
                                    <th>E-mail:</th>
                                    <td><?php echo $user->email; ?></td>
                                </tr>
                                <tr>
                                    <th>Tipo:</th>
                                    <td>
                                        <?php if ($user->is_admin == 1): ?>
                                            <span class="badge bg-danger">Administrador</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Usuário</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <?php echo form_open('users/delete/'.$user->id); ?>
                        <input type="hidden" name="confirm_delete" value="1">
                        
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Sim, Excluir Usuário
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
