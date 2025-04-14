<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tomadores extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Verificar se o usuário está logado
        if(!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        $this->load->model('Tomador_model');
        $this->load->model('Imovel_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function index() {
        $data['title'] = 'Gerenciar Tomadores';
        $data['tomadores'] = $this->Tomador_model->get_all();
        
        $this->load->view('templates/header', $data);
        $this->load->view('tomadores/index', $data);
        $this->load->view('templates/footer');
    }

    public function view($id = NULL) {
        if(!$id) {
            show_404();
        }
        
        $data['title'] = 'Detalhes do Tomador';
        $data['tomador'] = $this->Tomador_model->get_by_id($id);
        
        if(empty($data['tomador'])) {
            show_404();
        }
        
        // Obter imóveis associados a este tomador
        $data['imoveis'] = $this->Imovel_model->get_by_tomador($id);
        
        $this->load->view('templates/header', $data);
        $this->load->view('tomadores/view', $data);
        $this->load->view('templates/footer');
    }

    public function create() {
        $data['title'] = 'Adicionar Tomador';
        
        $this->form_validation->set_rules('razao_social', 'Razão Social/Nome', 'required');
        $this->form_validation->set_rules('email', 'Email', 'valid_email');
        
        // Validar CPF ou CNPJ
        if($this->input->post('tipo_documento') == 'cpf') {
            $this->form_validation->set_rules('cpf', 'CPF', 'required|callback_validate_cpf');
        } else {
            $this->form_validation->set_rules('cnpj', 'CNPJ', 'required|callback_validate_cnpj');
        }
        
        if($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('tomadores/create', $data);
            $this->load->view('templates/footer');
        } else {
            $this->Tomador_model->save($_POST);
            $this->session->set_flashdata('success', 'Tomador cadastrado com sucesso!');
            redirect('tomadores');
        }
    }

    public function edit($id = NULL) {
        if(!$id) {
            show_404();
        }
        
        $data['title'] = 'Editar Tomador';
        $data['tomador'] = $this->Tomador_model->get_by_id($id);
        
        if(empty($data['tomador'])) {
            show_404();
        }
        
        $this->form_validation->set_rules('razao_social', 'Razão Social/Nome', 'required');
        $this->form_validation->set_rules('email', 'Email', 'valid_email');
        
        // Validar CPF ou CNPJ
        if($this->input->post('tipo_documento') == 'cpf') {
            $this->form_validation->set_rules('cpf', 'CPF', 'required|callback_validate_cpf');
        } elseif($this->input->post('tipo_documento') == 'cnpj') {
            $this->form_validation->set_rules('cnpj', 'CNPJ', 'required|callback_validate_cnpj');
        }
        
        if($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('tomadores/edit', $data);
            $this->load->view('templates/footer');
        } else {
            $this->Tomador_model->update($id, $_POST);
            $this->session->set_flashdata('success', 'Tomador atualizado com sucesso!');
            redirect('tomadores');
        }
    }

    public function delete($id = NULL) {
        if(!$id) {
            show_404();
        }
        
        // Verificar se existem imóveis vinculados
        $imoveis = $this->Imovel_model->get_by_tomador($id);
        if(!empty($imoveis)) {
            $this->session->set_flashdata('error', 'Não é possível excluir este tomador porque existem imóveis vinculados a ele.');
            redirect('tomadores');
            return;
        }
        
        $result = $this->Tomador_model->delete($id);
        
        if($result) {
            $this->session->set_flashdata('success', 'Tomador excluído com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Não foi possível excluir o tomador. Verifique se existem notas fiscais associadas.');
        }
        
        redirect('tomadores');
    }
    

    
    // Função para validar CPF
    public function validate_cpf($cpf) {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        
        if(strlen($cpf) != 11) {
            $this->form_validation->set_message('validate_cpf', 'O CPF deve conter 11 dígitos.');
            return FALSE;
        }
        
        // Verifica se foi informado todos os dígitos corretamente
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            $this->form_validation->set_message('validate_cpf', 'CPF inválido.');
            return FALSE;
        }
        
        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                $this->form_validation->set_message('validate_cpf', 'CPF inválido.');
                return FALSE;
            }
        }
        
        return TRUE;
    }
    
    // Função para validar CNPJ
    public function validate_cnpj($cnpj) {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        
        if(strlen($cnpj) != 14) {
            $this->form_validation->set_message('validate_cnpj', 'O CNPJ deve conter 14 dígitos.');
            return FALSE;
        }
        
        // Verifica se foi informado todos os dígitos corretamente
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            $this->form_validation->set_message('validate_cnpj', 'CNPJ inválido.');
            return FALSE;
        }
        
        // Primeiro dígito verificador
        $soma = 0;
        $multiplicador = 5;
        
        for ($i = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $multiplicador;
            $multiplicador = ($multiplicador == 2) ? 9 : $multiplicador - 1;
        }
        
        $resto = $soma % 11;
        $dv1 = ($resto < 2) ? 0 : 11 - $resto;
        
        // Segundo dígito verificador
        $soma = 0;
        $multiplicador = 6;
        
        for ($i = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $multiplicador;
            $multiplicador = ($multiplicador == 2) ? 9 : $multiplicador - 1;
        }
        
        $resto = $soma % 11;
        $dv2 = ($resto < 2) ? 0 : 11 - $resto;
        
        if ($cnpj[12] != $dv1 || $cnpj[13] != $dv2) {
            $this->form_validation->set_message('validate_cnpj', 'CNPJ inválido.');
            return FALSE;
        }
        
        return TRUE;
    }
}
