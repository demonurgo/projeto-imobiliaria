<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inquilinos extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // Verificar se o usuário está logado
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        
        // Carregar modelos necessários
        $this->load->model('Inquilino_model');
        $this->load->model('Imovel_model');
        $this->load->model('Nota_model');
        
        // Carregar bibliotecas e helpers
        $this->load->helper(array('form', 'url', 'date'));
        $this->load->library(array('form_validation', 'session'));
    }
    
    public function index() {
        $data['title'] = 'Inquilinos';
        $data['active'] = 'inquilinos';
        $data['inquilinos'] = $this->Inquilino_model->get_all();
        
        $this->load->view('templates/header', $data);
        $this->load->view('inquilinos/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function create() {
        $data['title'] = 'Cadastrar Inquilino';
        $data['active'] = 'inquilinos';
        
        // Configurar regras de validação
        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('tipo_documento', 'Tipo de Documento', 'required');
        $this->form_validation->set_rules('documento', 'Documento (CPF/CNPJ)', 'required|callback_validate_documento');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('inquilinos/create', $data);
            $this->load->view('templates/footer');
        } else {
            // Preparar os dados para salvamento
            $inquilino_data = array(
                'nome' => $this->input->post('nome'),
                'telefone' => $this->input->post('telefone'),
                'email' => $this->input->post('email'),
                'endereco' => $this->input->post('endereco'),
                'observacoes' => $this->input->post('observacoes')
            );
            
            // Verificar tipo de documento
            $tipo_documento = $this->input->post('tipo_documento');
            $documento = preg_replace('/[^0-9]/', '', $this->input->post('documento'));
            
            if ($tipo_documento == 'cpf') {
                $inquilino_data['cpf'] = $documento;
            } else {
                $inquilino_data['cnpj_inquilino'] = $documento; // Adaptar para o modelo atual
            }
            
            $inserted_id = $this->Inquilino_model->save($inquilino_data);
            
            if ($inserted_id) {
                $this->session->set_flashdata('success', 'Inquilino cadastrado com sucesso.');
                redirect('inquilinos');
            } else {
                $this->session->set_flashdata('error', 'Erro ao cadastrar inquilino.');
                $this->load->view('templates/header', $data);
                $this->load->view('inquilinos/create', $data);
                $this->load->view('templates/footer');
            }
        }
    }
    
    public function edit($id) {
        $inquilino = $this->Inquilino_model->get_by_id($id);
        
        if (!$inquilino) {
            $this->session->set_flashdata('error', 'Inquilino não encontrado.');
            redirect('inquilinos');
        }
        
        $data['title'] = 'Editar Inquilino';
        $data['active'] = 'inquilinos';
        $data['inquilino'] = $inquilino;
        
        // Detectar tipo de documento (CPF ou CNPJ) baseado no tamanho
        $data['tipo_documento'] = (strlen($inquilino['cpf_cnpj']) == 11) ? 'cpf' : 'cnpj';
        $data['documento'] = $inquilino['cpf_cnpj']; // Usando o campo CPF para ambos os tipos
        
        // Configurar regras de validação
        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('tipo_documento', 'Tipo de Documento', 'required');
        $this->form_validation->set_rules('documento', 'Documento (CPF/CNPJ)', 'required|callback_validate_documento');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('inquilinos/edit', $data);
            $this->load->view('templates/footer');
        } else {
            // Preparar os dados para atualização
            $update_data = array(
                'nome' => $this->input->post('nome'),
                'telefone' => $this->input->post('telefone'),
                'email' => $this->input->post('email'),
                'endereco' => $this->input->post('endereco'),
                'observacoes' => $this->input->post('observacoes')
            );
            
            // Verificar tipo de documento
            $tipo_documento = $this->input->post('tipo_documento');
            $documento = preg_replace('/[^0-9]/', '', $this->input->post('documento'));
            
            // Verificar se o documento foi alterado
            if ($documento != $inquilino['cpf']) {
                // Verificar se o documento já está em uso por outro inquilino
                $existe = ($tipo_documento == 'cpf') ? 
                    $this->Inquilino_model->get_by_cpf($documento) : 
                    $this->Inquilino_model->get_by_cpf($documento);
                
                if ($existe && $existe['id'] != $id) {
                    $this->session->set_flashdata('error', 'Documento já cadastrado para outro inquilino.');
                    $this->load->view('templates/header', $data);
                    $this->load->view('inquilinos/edit', $data);
                    $this->load->view('templates/footer');
                    return;
                }
                
                // Atualizar documento
                $update_data['cpf'] = $documento;
            }
            
            if ($this->Inquilino_model->update($id, $update_data)) {
                $this->session->set_flashdata('success', 'Inquilino atualizado com sucesso.');
                redirect('inquilinos');
            } else {
                $this->session->set_flashdata('error', 'Erro ao atualizar inquilino.');
                $this->load->view('templates/header', $data);
                $this->load->view('inquilinos/edit', $data);
                $this->load->view('templates/footer');
            }
        }
    }
    
    public function view($id) {
        $inquilino = $this->Inquilino_model->get_by_id($id);
        
        if (!$inquilino) {
            $this->session->set_flashdata('error', 'Inquilino não encontrado.');
            redirect('inquilinos');
        }
        
        $data['title'] = 'Detalhes do Inquilino';
        $data['active'] = 'inquilinos';
        $data['inquilino'] = $inquilino;
        
        // Detectar tipo de documento (CPF ou CNPJ) baseado no tamanho
        $data['tipo_documento'] = (strlen($inquilino['cpf_cnpj']) == 11) ? 'cpf' : 'cnpj';
        
        // Buscar imóveis associados a este inquilino
        $this->load->model('Imovel_model');
        $data['imoveis'] = $this->Imovel_model->get_by_inquilino($id);
        
        // Buscar notas fiscais associadas a este inquilino
        $this->load->model('Nota_model');
        $data['notas'] = $this->Nota_model->get_by_inquilino($id);
        
        $this->load->view('templates/header', $data);
        $this->load->view('inquilinos/view', $data);
        $this->load->view('templates/footer');
    }
    
    public function delete($id) {
        $inquilino = $this->Inquilino_model->get_by_id($id);
        
        if (!$inquilino) {
            $this->session->set_flashdata('error', 'Inquilino não encontrado.');
            redirect('inquilinos');
        }
        
        // Verificar se existem imóveis ou notas associadas
        $this->db->where('inquilino_id', $id);
        $imoveis = $this->db->get('imoveis')->num_rows();
        
        $this->db->where('inquilino_id', $id);
        $notas = $this->db->get('notas')->num_rows();
        
        if ($imoveis > 0 || $notas > 0) {
            $this->session->set_flashdata('error', 'Não é possível excluir o inquilino pois existem imóveis ou notas fiscais associadas.');
            redirect('inquilinos');
        }
        
        if ($this->Inquilino_model->delete($id)) {
            $this->session->set_flashdata('success', 'Inquilino excluído com sucesso.');
        } else {
            $this->session->set_flashdata('error', 'Erro ao excluir inquilino.');
        }
        
        redirect('inquilinos');
    }
    

    
    // Função de callback para validar documento (CPF ou CNPJ)
    public function validate_documento($documento) {
        $tipo_documento = $this->input->post('tipo_documento');
        $documento = preg_replace('/[^0-9]/', '', $documento);
        
        // Validar formato do documento
        if ($tipo_documento == 'cpf' && strlen($documento) != 11) {
            $this->form_validation->set_message('validate_documento', 'O CPF deve conter 11 dígitos numéricos.');
            return FALSE;
        } else if ($tipo_documento == 'cnpj' && strlen($documento) != 14) {
            $this->form_validation->set_message('validate_documento', 'O CNPJ deve conter 14 dígitos numéricos.');
            return FALSE;
        }
        
        // Verificar se o documento já está cadastrado para outro inquilino
        // Ignorar na edição quando o documento não foi alterado
        $id = $this->input->post('id');
        $inquilino = $this->Inquilino_model->get_by_cpf($documento);
        
        if ($inquilino && (!$id || $inquilino['id'] != $id)) {
            $this->form_validation->set_message('validate_documento', 'Este documento já está cadastrado para outro inquilino.');
            return FALSE;
        }
        
        return TRUE;
    }
}
