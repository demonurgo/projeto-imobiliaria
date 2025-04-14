<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        require_login(); // Verificar se o usuário está logado
        
        // Carregar os modelos necessários para os contadores
        $this->load->model('Nota_model');
        $this->load->model('Inquilino_model');
        $this->load->model('Imovel_model');
    }

    public function index() {
        $data['title'] = 'Dashboard - Sistema NFSe-DIMOB';
        $data['user'] = get_current_user();
        
        // Adiciona dados da sessão para exibição fallback
        if (!$data['user']) {
            log_message('error', 'Dashboard: user_id na sessão = ' . $this->session->userdata('user_id'));
            // Cria um objeto de usuário fallback
            $data['user'] = (object)[
                'name' => $this->session->userdata('name') ?: 'Usuário'
            ];
        }
        
        // Carregar contadores para o dashboard
        $data['total_notas'] = $this->db->count_all('notas');
        $data['total_inquilinos'] = $this->db->count_all('inquilinos');
        $data['total_imoveis'] = $this->db->count_all('imoveis');
        
        // Buscar notas recentes para mostrar nas atividades
        $this->db->order_by('data_emissao', 'DESC');
        $this->db->limit(5);
        $data['notas_recentes'] = $this->db->get('notas')->result_array();
        
        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer');
    }
}
