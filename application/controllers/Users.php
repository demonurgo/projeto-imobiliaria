<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        require_login(); // Verificar se o usuário está logado
        
        $this->load->model('user_model');
        $this->load->library('form_validation');
    }

    public function index() {
        $data['title'] = 'Gerenciar Usuários - Sistema NFSe-DIMOB';
        $data['users'] = $this->user_model->get_all();
        
        // Obter o usuário atual e garantir que é um objeto válido
        $data['current_user'] = get_current_user();
        if (!$data['current_user']) {
            $data['current_user'] = (object)[
                'id' => $this->session->userdata('user_id'),
                'name' => $this->session->userdata('name')
            ];
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('users/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function create() {
        // Apenas administradores podem criar usuários
        require_admin();
        
        $data['title'] = 'Novo Usuário - Sistema NFSe-DIMOB';
        
        // Obter o usuário atual e garantir que é um objeto válido
        $data['current_user'] = get_current_user();
        if (!$data['current_user']) {
            $data['current_user'] = (object)[
                'id' => $this->session->userdata('user_id'),
                'name' => $this->session->userdata('name')
            ];
        }
        
        $this->form_validation->set_rules('username', 'Nome de Usuário', 'required|trim|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Senha', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirmação de Senha', 'required|matches[password]');
        $this->form_validation->set_rules('name', 'Nome Completo', 'required|trim');
        $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email|is_unique[users.email]');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('users/create', $data);
            $this->load->view('templates/footer');
        } else {
            $user_data = [
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'is_admin' => $this->input->post('is_admin') ? 1 : 0
            ];
            
            $user_id = $this->user_model->create($user_data);
            
            if ($user_id) {
                $this->session->set_flashdata('success', 'Usuário criado com sucesso!');
                redirect('users');
            } else {
                $this->session->set_flashdata('error', 'Erro ao criar usuário. Tente novamente.');
                redirect('users/create');
            }
        }
    }
    
    public function edit($id = NULL) {
        if (!$id) {
            show_404();
        }
        
        // Apenas administradores podem editar outros usuários
        if (!is_admin() && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar outros usuários.');
            redirect('users');
            return;
        }
        
        $data['user'] = $this->user_model->get($id);
        
        if (!$data['user']) {
            show_404();
        }
        
        // Os usuários não-admin só podem editar seu próprio perfil através da página de perfil
        if (!is_admin() && $this->session->userdata('user_id') != $id) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar outros usuários.');
            redirect('users');
        }
        
        // Administradores devem editar seu próprio perfil pela página de perfil
        if (is_admin() && $this->session->userdata('user_id') == $id) {
            $this->session->set_flashdata('error', 'Para editar seu próprio perfil, use a opção "Meu Perfil".');
            redirect('users');
        }
        
        $data['title'] = 'Editar Usuário - Sistema NFSe-DIMOB';
        $data['current_user'] = (object)[
            'id' => $this->session->userdata('user_id'),
            'name' => $this->session->userdata('name')
        ];
        
        // Configurar regras de validação
        $this->form_validation->set_rules('username', 'Nome de Usuário', 'required|trim');
        if ($this->input->post('username') != $data['user']->username) {
            $this->form_validation->set_rules('username', 'Nome de Usuário', 'required|trim|is_unique[users.username]');
        }
        
        $this->form_validation->set_rules('name', 'Nome Completo', 'required|trim');
        $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
        if ($this->input->post('email') != $data['user']->email) {
            $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email|is_unique[users.email]');
        }
        
        if ($this->input->post('password')) {
            $this->form_validation->set_rules('password', 'Senha', 'min_length[6]');
            $this->form_validation->set_rules('confirm_password', 'Confirmação de Senha', 'matches[password]');
        }
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('users/edit', $data);
            $this->load->view('templates/footer');
        } else {
            $user_data = [
                'username' => $this->input->post('username'),
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'is_admin' => $this->input->post('is_admin') ? 1 : 0,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Apenas atualiza a senha se uma nova senha foi fornecida
            if ($this->input->post('password')) {
                $user_data['password'] = $this->input->post('password');
            }
            
            $updated = $this->user_model->update($id, $user_data);
            
            if ($updated) {
                $this->session->set_flashdata('success', 'Usuário atualizado com sucesso!');
                redirect('users');
            } else {
                $this->session->set_flashdata('error', 'Erro ao atualizar usuário. Tente novamente.');
                redirect('users/edit/' . $id);
            }
        }
    }
    
    public function delete($id = NULL) {
        // Apenas administradores podem excluir usuários
        require_admin();
        
        if (!$id) {
            show_404();
        }
        
        $user = $this->user_model->get($id);
        
        if (!$user) {
            show_404();
        }
        
        // Não permitir excluir o próprio usuário
        if ($this->session->userdata('user_id') == $id) {
            $this->session->set_flashdata('error', 'Você não pode excluir seu próprio usuário!');
            redirect('users');
            return;
        }
        
        // Verificação de segurança adicional via POST
        if ($this->input->post('confirm_delete')) {
            $deleted = $this->user_model->delete($id);
            
            if ($deleted) {
                $this->session->set_flashdata('success', 'Usuário excluído com sucesso!');
            } else {
                $this->session->set_flashdata('error', 'Erro ao excluir usuário. Tente novamente.');
            }
            
            redirect('users');
        } else {
            $data['title'] = 'Confirmar Exclusão - Sistema NFSe-DIMOB';
            $data['user'] = $user;
            $data['current_user'] = (object)[
                'id' => $this->session->userdata('user_id'),
                'name' => $this->session->userdata('name')
            ];
            
            $this->load->view('templates/header', $data);
            $this->load->view('users/delete', $data);
            $this->load->view('templates/footer');
        }
    }
    
    public function profile() {
        $user_id = $this->session->userdata('user_id');
        $user = $this->user_model->get($user_id);
        
        if (!$user) {
            $this->session->set_flashdata('error', 'Não foi possível carregar seu perfil. Tente fazer login novamente.');
            redirect('auth/logout');
            return;
        }
        
        $data['title'] = 'Meu Perfil - Sistema NFSe-DIMOB';
        $data['user'] = $user;
        $data['current_user'] = (object)[
            'id' => $user_id,
            'name' => $this->session->userdata('name')
        ];
        
        // Configurar regras de validação
        $this->form_validation->set_rules('name', 'Nome Completo', 'required|trim');
        $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
        if ($this->input->post('email') != $user->email) {
            $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email|is_unique[users.email]');
        }
        
        if ($this->input->post('password')) {
            $this->form_validation->set_rules('password', 'Nova Senha', 'min_length[6]');
            $this->form_validation->set_rules('confirm_password', 'Confirmação de Senha', 'matches[password]');
            $this->form_validation->set_rules('current_password', 'Senha Atual', 'required|callback_verify_current_password');
        }
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('users/profile', $data);
            $this->load->view('templates/footer');
        } else {
            $user_data = [
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Apenas atualiza a senha se uma nova senha foi fornecida
            if ($this->input->post('password')) {
                $user_data['password'] = $this->input->post('password');
            }
            
            $updated = $this->user_model->update($user_id, $user_data);
            
            if ($updated) {
                $this->session->set_flashdata('success', 'Perfil atualizado com sucesso!');
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Erro ao atualizar perfil. Tente novamente.');
                redirect('users/profile');
            }
        }
    }
    
    // Callback para verificar a senha atual
    public function verify_current_password($password) {
        $user_id = $this->session->userdata('user_id');
        $user = $this->user_model->get($user_id);
        
        if (!$user || !password_verify($password, $user->password)) {
            $this->form_validation->set_message('verify_current_password', 'A senha atual está incorreta.');
            return FALSE;
        }
        
        return TRUE;
    }
}
