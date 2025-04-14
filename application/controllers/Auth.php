<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index() {
        // Se já estiver logado, redireciona para o dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        
        $this->load->view('auth/login');
    }

    public function login() {
        // Se já estiver logado, redireciona para o dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('username', 'Usuário', 'required|trim');
        $this->form_validation->set_rules('password', 'Senha', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/login');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

			//echo $username;
			//echo $password;
            
            $user = $this->user_model->authenticate($username, $password);
            
            if ($user) {
                // Criar dados da sessão
                $session_data = [
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'name' => $user->name,
                    'is_admin' => $user->is_admin,
                    'logged_in' => TRUE
                ];
                
                $this->session->set_userdata($session_data);
                
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Usuário ou senha inválidos');
                $this->load->view('auth/login');
            }
        }
    }

    public function logout() {
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('is_admin');
        
        redirect('auth');
    }
    
    // Método para criar o primeiro usuário admin (só deve ser usado na instalação)
    public function create_admin() {
        // Verificar se já existe usuário no sistema
        $users = $this->user_model->get_all();
        
        // Força a deleção do usuário admin existente (apenas para teste)
        $this->db->query("DELETE FROM users WHERE username='admin'");
        
        // Cria o usuário admin com senha direta para teste
        $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, email, name, is_admin) 
                VALUES ('admin', '{$password_hash}', 'admin@example.com', 'Administrador', 1)";
        
        $this->db->query($sql);
        $admin_id = $this->db->insert_id();
        
        if ($admin_id) {
            echo 'Usuário admin criado com sucesso. ID: ' . $admin_id;
            echo '<br>Username: admin<br>Senha: admin123';
            echo '<br><a href="' . base_url('auth') . '">Ir para o login</a>';
        } else {
            echo 'Erro ao criar usuário admin: ' . $this->db->error()['message'];
        }
    }
}
