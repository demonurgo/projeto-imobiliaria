<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prestadores extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Verificar se o usuário está logado
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        
        // Carregar modelos necessários
        $this->load->model('Prestador_model');
        $this->load->model('Nota_model');
        
        // Carregar bibliotecas e helpers
        $this->load->helper(array('form', 'url', 'date'));
        $this->load->library(array('form_validation', 'session'));
    }

    public function index() {
        $data['title'] = 'Prestadores';
        $data['active'] = 'prestadores';
        $data['prestadores'] = $this->Prestador_model->get_all();
        
        $this->load->view('templates/header', $data);
        $this->load->view('prestadores/index', $data);
        $this->load->view('templates/footer');
    }

    public function create() {
        $data['title'] = 'Cadastrar Prestador';
        $data['active'] = 'prestadores';
        
        // Configurar regras de validação
        $this->form_validation->set_rules('cnpj', 'CNPJ', 'required|callback_validate_cnpj');
        $this->form_validation->set_rules('razao_social', 'Razão Social', 'required');
        $this->form_validation->set_rules('email', 'Email', 'valid_email');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('prestadores/create', $data);
            $this->load->view('templates/footer');
        } else {
            // Preparar os dados do prestador
            $prestador_data = array(
                'cnpj' => preg_replace('/[^0-9]/', '', $this->input->post('cnpj')),
                'inscricao_municipal' => $this->input->post('inscricao_municipal'),
                'razao_social' => $this->input->post('razao_social'),
                'endereco' => $this->input->post('endereco'),
                'numero' => $this->input->post('numero'),
                'complemento' => $this->input->post('complemento'),
                'bairro' => $this->input->post('bairro'),
                'codigo_municipio' => $this->input->post('codigo_municipio'),
                'uf' => $this->input->post('uf'),
                'cep' => preg_replace('/[^0-9]/', '', $this->input->post('cep')),
                'telefone' => $this->input->post('telefone'),
                'email' => $this->input->post('email'),
                'created_at' => date('Y-m-d H:i:s')
            );
            
            // Salvar prestador
            $prestador_id = $this->Prestador_model->save($prestador_data);
            
            if ($prestador_id) {
                $this->session->set_flashdata('success', 'Prestador cadastrado com sucesso.');
                redirect('prestadores');
            } else {
                $this->session->set_flashdata('error', 'Erro ao cadastrar prestador.');
                $this->load->view('templates/header', $data);
                $this->load->view('prestadores/create', $data);
                $this->load->view('templates/footer');
            }
        }
    }

    public function edit($id) {
        $data['prestador'] = $this->Prestador_model->get_by_id($id);
        
        if (empty($data['prestador'])) {
            $this->session->set_flashdata('error', 'Prestador não encontrado.');
            redirect('prestadores');
        }
        
        $data['title'] = 'Editar Prestador';
        $data['active'] = 'prestadores';
        
        // Configurar regras de validação
        $this->form_validation->set_rules('cnpj', 'CNPJ', 'required|callback_validate_cnpj');
        $this->form_validation->set_rules('razao_social', 'Razão Social', 'required');
        $this->form_validation->set_rules('email', 'Email', 'valid_email');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('prestadores/edit', $data);
            $this->load->view('templates/footer');
        } else {
            // Preparar os dados do prestador
            $prestador_data = array(
                'cnpj' => preg_replace('/[^0-9]/', '', $this->input->post('cnpj')),
                'inscricao_municipal' => $this->input->post('inscricao_municipal'),
                'razao_social' => $this->input->post('razao_social'),
                'endereco' => $this->input->post('endereco'),
                'numero' => $this->input->post('numero'),
                'complemento' => $this->input->post('complemento'),
                'bairro' => $this->input->post('bairro'),
                'codigo_municipio' => $this->input->post('codigo_municipio'),
                'uf' => $this->input->post('uf'),
                'cep' => preg_replace('/[^0-9]/', '', $this->input->post('cep')),
                'telefone' => $this->input->post('telefone'),
                'email' => $this->input->post('email'),
                'updated_at' => date('Y-m-d H:i:s')
            );
            
            // Atualizar prestador
            if ($this->Prestador_model->update($id, $prestador_data)) {
                $this->session->set_flashdata('success', 'Prestador atualizado com sucesso.');
                redirect('prestadores');
            } else {
                $this->session->set_flashdata('error', 'Erro ao atualizar prestador.');
                $this->load->view('templates/header', $data);
                $this->load->view('prestadores/edit', $data);
                $this->load->view('templates/footer');
            }
        }
    }

    public function view($id) {
        $data['prestador'] = $this->Prestador_model->get_by_id($id);
        
        if (empty($data['prestador'])) {
            $this->session->set_flashdata('error', 'Prestador não encontrado.');
            redirect('prestadores');
        }
        
        $data['title'] = 'Detalhes do Prestador';
        $data['active'] = 'prestadores';
        
        // Buscar notas fiscais relacionadas a este prestador
        $this->db->where('prestador_id', $id);
        $data['notas'] = $this->Nota_model->get_by_prestador($id);
        
        $this->load->view('templates/header', $data);
        $this->load->view('prestadores/view', $data);
        $this->load->view('templates/footer');
    }

    public function delete($id) {
        $prestador = $this->Prestador_model->get_by_id($id);
        
        if (empty($prestador)) {
            $this->session->set_flashdata('error', 'Prestador não encontrado.');
            redirect('prestadores');
        }
        
        // Verificar se há notas associadas
        $this->db->where('prestador_id', $id);
        $notas = $this->db->get('notas')->num_rows();
        
        if ($notas > 0) {
            $this->session->set_flashdata('error', 'Não é possível excluir pois existem notas fiscais associadas a este prestador.');
            redirect('prestadores');
        }
        
        if ($this->Prestador_model->delete($id)) {
            $this->session->set_flashdata('success', 'Prestador excluído com sucesso.');
        } else {
            $this->session->set_flashdata('error', 'Erro ao excluir prestador.');
        }
        
        redirect('prestadores');
    }
    
    // Função para validar CNPJ
    public function validate_cnpj($cnpj) {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        
        // Verificar se o CNPJ tem 14 dígitos
        if (strlen($cnpj) != 14) {
            $this->form_validation->set_message('validate_cnpj', 'O CNPJ deve conter 14 dígitos.');
            return FALSE;
        }
        
        // Verificar se todos os dígitos são iguais (ex: 11111111111111)
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            $this->form_validation->set_message('validate_cnpj', 'CNPJ inválido.');
            return FALSE;
        }
        
        // Cálculo de validação
        for ($t = 12; $t < 14; $t++) {
            $d = 0;
            $c = 0;
            for ($m = $t - 7; $m >= 2; $m--, $c++) {
                $d += $cnpj[$c] * $m;
            }
            for ($m = 9; $m >= 2; $m--, $c++) {
                $d += $cnpj[$c] * $m;
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cnpj[$c] != $d) {
                $this->form_validation->set_message('validate_cnpj', 'CNPJ inválido.');
                return FALSE;
            }
        }
        
        return TRUE;
    }
}
