<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Sistema NFSe-DIMOB'; ?></title>
    
    <!-- CSS -->
    <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo base_url('dashboard'); ?>">NFSe-DIMOB</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $this->uri->segment(1) == 'dashboard' ? 'active' : ''; ?>" href="<?php echo base_url('dashboard'); ?>">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <?php if (is_admin()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cogs"></i> Administração
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="<?php echo base_url('users'); ?>">Usuários</a></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('settings'); ?>">Configurações</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $this->uri->segment(1) == 'importacao' ? 'active' : ''; ?>" href="<?php echo base_url('importacao'); ?>">
                            <i class="fas fa-file-import"></i> Importar XML
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $this->uri->segment(1) == 'dimob' ? 'active' : ''; ?>" href="<?php echo base_url('dimob'); ?>">
                            <i class="fas fa-file-export"></i> Gerar DIMOB
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="entidadesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-database"></i> Cadastros
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="entidadesDropdown">
                            <li><a class="dropdown-item" href="<?php echo base_url('prestadores'); ?>">Prestadores</a></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('tomadores'); ?>">Tomadores</a></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('inquilinos'); ?>">Inquilinos</a></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('imoveis'); ?>">Imóveis</a></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('notas'); ?>">Notas Fiscais</a></li>
                        </ul>
                    </li>
                </ul>
                
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i> <?php echo $this->session->userdata('name'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?php echo base_url('profile'); ?>"><i class="fas fa-id-card"></i> Meu Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('auth/logout'); ?>"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $this->session->flashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $this->session->flashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
