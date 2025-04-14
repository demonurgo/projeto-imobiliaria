<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Imoveis extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // Verificar se o usuário está logado
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        
        // Carregar modelos necessários
        $this->load->model('Imovel_model');
        $this->load->model('Inquilino_model');
        $this->load->model('Tomador_model');
        $this->load->model('Nota_model');
        
        // Carregar bibliotecas e helpers
        $this->load->helper(array('form', 'url', 'date'));
        $this->load->library(array('form_validation', 'session'));
    }
    
    public function index() {
        $data['title'] = 'Imóveis';
        $data['active'] = 'imoveis';
        $data['imoveis'] = $this->Imovel_model->get_all();
        
        $this->load->view('templates/header', $data);
        $this->load->view('imoveis/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function create() {
        $data['title'] = 'Cadastrar Imóvel';
        $data['active'] = 'imoveis';
        $data['inquilinos'] = $this->Inquilino_model->get_all();
        $data['tomadores'] = $this->Tomador_model->get_all();
        
        // Configurar regras de validação
        $this->form_validation->set_rules('endereco', 'Endereço', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('imoveis/create', $data);
            $this->load->view('templates/footer');
        } else {
            // Preparar os dados para salvamento
            $imovel_data = array(
                'endereco' => $this->input->post('endereco'),
                'numero' => $this->input->post('numero'),
                'complemento' => $this->input->post('complemento'),
                'bairro' => $this->input->post('bairro'),
                'cidade' => $this->input->post('cidade'),
                'uf' => $this->input->post('uf'),
                'cep' => preg_replace('/[^0-9]/', '', $this->input->post('cep')),
                'inquilino_id' => $this->input->post('inquilino_id') ? $this->input->post('inquilino_id') : null,
                'tomador_id' => $this->input->post('tomador_id') ? $this->input->post('tomador_id') : null, // Adicionar tomador_id
                'valor_aluguel' => $this->input->post('valor_aluguel'),
                'codigo_referencia' => $this->input->post('codigo_referencia'),
                'observacoes' => $this->input->post('observacoes')
            );
            
            // Se não tiver código de referência, gerar um
            if (empty($imovel_data['codigo_referencia'])) {
                $imovel_data['codigo_referencia'] = 'IMOV-' . substr(uniqid(), -6);
            }
            
            $inserted_id = $this->Imovel_model->save($imovel_data);
            
            if ($inserted_id) {
                $this->session->set_flashdata('success', 'Imóvel cadastrado com sucesso.');
                redirect('imoveis');
            } else {
                $this->session->set_flashdata('error', 'Erro ao cadastrar imóvel.');
                $this->load->view('templates/header', $data);
                $this->load->view('imoveis/create', $data);
                $this->load->view('templates/footer');
            }
        }
    }
    
    public function edit($id) {
        $imovel = $this->Imovel_model->get_by_id($id);
        
        if (!$imovel) {
            $this->session->set_flashdata('error', 'Imóvel não encontrado.');
            redirect('imoveis');
        }
        
        $data['title'] = 'Editar Imóvel';
        $data['active'] = 'imoveis';
        $data['imovel'] = $imovel;
        $data['inquilinos'] = $this->Inquilino_model->get_all();
        $data['tomadores'] = $this->Tomador_model->get_all();
        
        // Configurar regras de validação
        $this->form_validation->set_rules('endereco', 'Endereço', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('imoveis/edit', $data);
            $this->load->view('templates/footer');
        } else {
            // Preparar os dados para atualização
            $update_data = array(
                'endereco' => $this->input->post('endereco'),
                'numero' => $this->input->post('numero'),
                'complemento' => $this->input->post('complemento'),
                'bairro' => $this->input->post('bairro'),
                'cidade' => $this->input->post('cidade'),
                'uf' => $this->input->post('uf'),
                'cep' => preg_replace('/[^0-9]/', '', $this->input->post('cep')),
                'inquilino_id' => $this->input->post('inquilino_id') ? $this->input->post('inquilino_id') : null,
                'tomador_id' => $this->input->post('tomador_id') ? $this->input->post('tomador_id') : null, // Adicionar tomador_id
                'valor_aluguel' => $this->input->post('valor_aluguel'),
                'codigo_referencia' => $this->input->post('codigo_referencia'),
                'observacoes' => $this->input->post('observacoes')
            );
            
            // Se não tiver código de referência, gerar um
            if (empty($update_data['codigo_referencia'])) {
                $update_data['codigo_referencia'] = 'IMOV-' . substr(uniqid(), -6);
            }
            
            if ($this->Imovel_model->update($id, $update_data)) {
                $this->session->set_flashdata('success', 'Imóvel atualizado com sucesso.');
                redirect('imoveis');
            } else {
                $this->session->set_flashdata('error', 'Erro ao atualizar imóvel.');
                $this->load->view('templates/header', $data);
                $this->load->view('imoveis/edit', $data);
                $this->load->view('templates/footer');
            }
        }
    }
    
    public function view($id) {
        $imovel = $this->Imovel_model->get_by_id($id);
        
        if (!$imovel) {
            $this->session->set_flashdata('error', 'Imóvel não encontrado.');
            redirect('imoveis');
        }
        
        $data['title'] = 'Detalhes do Imóvel';
        $data['active'] = 'imoveis';
        $data['imovel'] = $imovel;
        
        // Buscar notas fiscais associadas a este imóvel
        $data['notas'] = $this->Nota_model->get_by_imovel($id);
        
        $this->load->view('templates/header', $data);
        $this->load->view('imoveis/view', $data);
        $this->load->view('templates/footer');
    }
    
    public function delete($id) {
        $imovel = $this->Imovel_model->get_by_id($id);
        
        if (!$imovel) {
            $this->session->set_flashdata('error', 'Imóvel não encontrado.');
            redirect('imoveis');
        }
        
        // Verificar se existem notas associadas
        $this->db->where('imovel_id', $id);
        $notas = $this->db->get('notas')->num_rows();
        
        if ($notas > 0) {
            $this->session->set_flashdata('error', 'Não é possível excluir o imóvel pois existem notas fiscais associadas.');
            redirect('imoveis');
        }
        
        if ($this->Imovel_model->delete($id)) {
            $this->session->set_flashdata('success', 'Imóvel excluído com sucesso.');
        } else {
            $this->session->set_flashdata('error', 'Erro ao excluir imóvel.');
        }
        
        redirect('imoveis');
    }
    
    // Filtrar imóveis por inquilino (para chamadas AJAX)
    public function filter_by_inquilino() {
        $inquilino_id = $this->input->post('inquilino_id');
        
        if (!$inquilino_id) {
            echo json_encode(array('success' => false, 'message' => 'ID do inquilino não fornecido.'));
            return;
        }
        
        $imoveis = $this->Imovel_model->get_by_inquilino($inquilino_id);
        
        echo json_encode(array(
            'success' => true,
            'imoveis' => $imoveis
        ));
    }
}
