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
        $this->load->model('Log_model');
        $this->load->model('Dimob_model');
        $this->load->model('Tomador_model');
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
        $data['total_tomadores'] = $this->db->count_all('tomadores');
        $data['total_inquilinos'] = $this->db->count_all('inquilinos');
        $data['total_imoveis'] = $this->db->count_all('imoveis');
        
        // Estatísticas DIMOB
        $data['total_dimob_enviado'] = $this->db->where('dimob_enviado', 1)->count_all_results('notas');
        $data['total_dimob_pendente'] = $data['total_notas'] - $data['total_dimob_enviado'];
        
        // Obtém o ano atual para DIMOB
        $ano_atual = date('Y');
        $data['ano_atual'] = $ano_atual;
        
        // Notas do ano atual
        $this->db->where('YEAR(competencia)', $ano_atual);
        $data['notas_ano_atual'] = $this->db->count_all_results('notas');
        
        // Buscar notas recentes para mostrar nas atividades
        $this->db->order_by('data_emissao', 'DESC');
        $this->db->limit(5);
        $data['notas_recentes'] = $this->db->get('notas')->result_array();
        
        // Buscar logs recentes de atividades
        $data['atividades_recentes'] = $this->Log_model->get_recent_logs(5);
        
        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer');
    }
}
