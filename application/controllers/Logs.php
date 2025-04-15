<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        // Verificar se o usuário está logado
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        
        // Verificar se o usuário é um administrador
        if (!$this->session->userdata('is_admin')) {
            $this->session->set_flashdata('error', 'Acesso restrito a administradores do sistema.');
            redirect('dashboard');
        }
        
        // Carregar os modelos e helpers necessários
        $this->load->model('Log_model');
        $this->load->model('User_model');
        $this->load->model('Nota_model');
        $this->load->helper(array('form', 'url', 'date', 'auth'));
        $this->load->library(array('pagination', 'form_validation'));
    }
    
    public function index() {
        $data['title'] = 'Log de Atividades';
        $data['active'] = 'logs';
        
        // Inicializar filtros a partir da URL
        $filters = array();
        
        // Configurar filtros
        if ($this->input->get('user_id')) {
            $filters['user_id'] = $this->input->get('user_id');
        }
        
        if ($this->input->get('module')) {
            $filters['module'] = $this->input->get('module');
        }
        
        if ($this->input->get('action')) {
            $filters['action'] = $this->input->get('action');
        }
        
        if ($this->input->get('tipo')) {
            $filters['tipo'] = $this->input->get('tipo');
        }
        
        if ($this->input->get('date_start')) {
            $filters['date_start'] = $this->input->get('date_start');
        }
        
        if ($this->input->get('date_end')) {
            $filters['date_end'] = $this->input->get('date_end');
        }
        
        if ($this->input->get('search')) {
            $filters['search'] = $this->input->get('search');
        }
        
        if ($this->input->get('batch_id')) {
            $filters['batch_id'] = $this->input->get('batch_id');
        }
        
        // Configuração de paginação
        $config['base_url'] = site_url('logs/index');
        $total_rows = $this->Log_model->count_logs($filters);
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 50;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string'] = TRUE;
        
        // Estilizar a paginação para o Bootstrap
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'Primeiro';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Último';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');
        
        $this->pagination->initialize($config);
        
        // Página atual
        $page = $this->input->get('page') ? $this->input->get('page') : 0;
        
        // Obter os logs com os filtros e paginação aplicados
        $data['logs'] = $this->Log_model->get_logs($filters, $config['per_page'], $page);
        $data['total_rows'] = $total_rows;
        
        // Dados para os filtros
        $data['users'] = $this->User_model->get_all_array();
        $data['filters'] = $filters;
        
        // Definir os módulos e ações disponíveis
        $data['modules'] = array(
            'prestadores' => 'Prestadores',
            'tomadores' => 'Tomadores',
            'inquilinos' => 'Inquilinos',
            'imoveis' => 'Imóveis',
            'notas' => 'Notas Fiscais',
            'users' => 'Usuários',
            'auth' => 'Autenticação',
            'system' => 'Sistema'
        );
        
        $data['actions'] = array(
            'create' => 'Criar',
            'update' => 'Atualizar',
            'delete' => 'Excluir',
            'login' => 'Login',
            'logout' => 'Logout',
            'import' => 'Importar',
            'export' => 'Exportar',
            'view' => 'Visualizar',
            'dimob' => 'DIMOB',
            'importacao_notas' => 'Importação de Notas',
            'inicio_processamento_xml' => 'Início de Processamento',
            'nota_importada' => 'Nota Importada',
            'conclusao_importacao' => 'Conclusão de Importação',
            'exclusao_nota' => 'Exclusão de Nota'
        );
        
        $this->load->view('templates/header', $data);
        $this->load->view('logs/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function view($id) {
        if (!$id) {
            show_404();
        }
        
        $data['title'] = 'Detalhes do Log';
        $data['active'] = 'logs';
        
        // Obter o log pelo ID
        $log = $this->Log_model->get_by_id($id);
        
        if (empty($log)) {
            show_404();
        }
        
        $data['log'] = $log;
        
        // Obter informações do usuário
        if ($log->user_id) {
            $data['usuario'] = $this->User_model->get($log->user_id);
        }
        
        // Decodificar dados adicionais
        if (!empty($log->data_after)) {
            $data['dados_adicionais'] = json_decode($log->data_after, true);
            
            // Se for um log de importação de notas, buscar as notas do lote
            if ($log->action == 'import' && isset($data['dados_adicionais']['batch_id'])) {
                $batch_id = $data['dados_adicionais']['batch_id'];
                // Limitar a 10 notas para não sobrecarregar a página
                $data['notas_do_lote'] = $this->Nota_model->get_by_batch_with_tomador($batch_id, 10);
            }
        }
        
        // Carregar as views
        $this->load->view('templates/header', $data);
        $this->load->view('logs/view', $data);
        $this->load->view('templates/footer');
    }
    
    public function clean() {
        // Verificação adicional de segurança (apenas admin pode limpar logs)
        if (!$this->session->userdata('is_admin')) {
            $this->session->set_flashdata('error', 'Operação restrita a administradores do sistema.');
            redirect('logs');
        }
        
        // Regras de validação
        $this->form_validation->set_rules('days', 'Dias', 'required|numeric|greater_than[30]');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', 'Não foi possível limpar os logs. O número de dias deve ser maior que 30.');
            redirect('logs');
        } else {
            // Limpar logs
            $days = $this->input->post('days');
            $removed = $this->Log_model->clean_old_logs($days);
            
            // Registrar a limpeza
            $this->Log_model->add_log('clean', 'system', null, 'Limpeza de logs antigos: removidos ' . $removed . ' registros com mais de ' . $days . ' dias');
            
            $this->session->set_flashdata('success', 'Foram removidos ' . $removed . ' registros de log com mais de ' . $days . ' dias.');
            redirect('logs');
        }
    }
    
    public function export() {
        // Inicializar filtros a partir da URL
        $filters = array();
        
        // Configurar filtros
        if ($this->input->get('user_id')) {
            $filters['user_id'] = $this->input->get('user_id');
        }
        
        if ($this->input->get('module')) {
            $filters['module'] = $this->input->get('module');
        }
        
        if ($this->input->get('action')) {
            $filters['action'] = $this->input->get('action');
        }
        
        if ($this->input->get('date_start')) {
            $filters['date_start'] = $this->input->get('date_start');
        }
        
        if ($this->input->get('date_end')) {
            $filters['date_end'] = $this->input->get('date_end');
        }
        
        if ($this->input->get('search')) {
            $filters['search'] = $this->input->get('search');
        }
        
        // Obter logs sem limite
        $logs = $this->Log_model->get_logs($filters, 0, 0);
        
        // Nome do arquivo
        $filename = 'logs_sistema_' . date('Y-m-d_H-i-s') . '.csv';
        
        // Headers para download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Criar um arquivo CSV diretamente no output
        $output = fopen('php://output', 'w');
        
        // Cabeçalho do CSV
        fputcsv($output, array(
            'ID',
            'Data/Hora',
            'Usuário',
            'Módulo',
            'Ação',
            'ID Registro',
            'Descrição',
            'IP',
            'User Agent'
        ));
        
        // Linhas de dados
        foreach ($logs as $log) {
            fputcsv($output, array(
                $log['id'],
                $log['created_at'],
                $log['user_name'] ?? 'Sistema',
                $log['module'],
                $log['action'],
                $log['record_id'] ?? '',
                $log['description'],
                $log['ip_address'],
                $log['user_agent']
            ));
        }
        
        // Registrar exportação
        $this->Log_model->add_log('export', 'system', null, 'Exportação de logs: ' . count($logs) . ' registros');
    }
    

    
    public function dashboard_widget() {
        // Essa função pode ser chamada via AJAX para atualizar o widget do dashboard
        $logs = $this->Log_model->get_recent_logs(5);
        
        $html = '';
        foreach ($logs as $log) {
            $html .= '<div class="activity-item">';
            $html .= '<div class="activity-content">';
            $html .= '<small class="text-muted">' . date('d/m/Y H:i', strtotime($log['created_at'])) . '</small>';
            $html .= '<p><strong>' . ($log['user_name'] ?? 'Sistema') . '</strong> ';
            
            // Texto da ação
            switch ($log['action']) {
                case 'create':
                    $html .= 'criou um novo registro em ';
                    break;
                case 'update':
                    $html .= 'atualizou um registro em ';
                    break;
                case 'delete':
                    $html .= 'excluiu um registro em ';
                    break;
                case 'login':
                    $html .= 'realizou login no sistema';
                    break;
                case 'logout':
                    $html .= 'saiu do sistema';
                    break;
                case 'import':
                    $html .= 'importou dados para ';
                    break;
                case 'export':
                    $html .= 'exportou dados de ';
                    break;
                default:
                    $html .= $log['action'] . ' em ';
            }
            
            // Texto do módulo
            if ($log['action'] != 'login' && $log['action'] != 'logout') {
                switch ($log['module']) {
                    case 'prestadores':
                        $html .= 'Prestadores';
                        break;
                    case 'tomadores':
                        $html .= 'Tomadores';
                        break;
                    case 'inquilinos':
                        $html .= 'Inquilinos';
                        break;
                    case 'imoveis':
                        $html .= 'Imóveis';
                        break;
                    case 'notas':
                        $html .= 'Notas Fiscais';
                        break;
                    case 'users':
                        $html .= 'Usuários';
                        break;
                    default:
                        $html .= $log['module'];
                }
            }
            
            if (!empty($log['description'])) {
                $html .= ': ' . $log['description'];
            }
            
            $html .= '</p>';
            $html .= '</div>';
            $html .= '</div>';
        }
        
        if (empty($logs)) {
            $html = '<div class="alert alert-info">Nenhuma atividade recente registrada.</div>';
        }
        
        // Se for uma requisição AJAX, retornar apenas o HTML
        if ($this->input->is_ajax_request()) {
            echo $html;
        } else {
            return $html;
        }
    }
}
