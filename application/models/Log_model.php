<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_model extends CI_Model {

    protected $table = 'system_logs';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Registra uma ação no sistema
     * 
     * @param string $tipo Tipo da ação (ex: 'importacao_notas', 'edicao', 'exclusao')
     * @param string $descricao Descrição detalhada da ação
     * @param string $entidade Nome da entidade afetada (ex: 'notas', 'prestadores')
     * @param int $entidade_id ID da entidade, se aplicável
     * @param array|null $dados_adicionais Dados adicionais para o log em formato array
     * @return int ID do log inserido
     */
    public function registrar_acao($tipo, $descricao, $entidade = null, $entidade_id = null, $dados_adicionais = null) {
        $data = [
            'action' => $tipo,
            'description' => $descricao,
            'module' => $entidade,
            'record_id' => $entidade_id,
            'data_after' => $dados_adicionais ? json_encode($dados_adicionais) : null,
            'user_id' => $this->session->userdata('user_id'),
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Registra logs específicos de importação de notas
     * 
     * @param string $descricao Descrição da importação
     * @param int $batch_id ID do lote de importação
     * @param array $resumo Resumo da importação (qtd. notas, prestadores, etc.)
     * @param string $arquivo Nome do arquivo importado
     * @return int ID do log inserido
     */
    public function registrar_importacao_notas($descricao, $batch_id, $resumo, $arquivo) {
        $dados_adicionais = [
            'batch_id' => $batch_id,
            'resumo' => $resumo,
            'arquivo' => $arquivo
        ];

        return $this->registrar_acao(
            'import',  // Usar 'import' como action em vez de 'importacao_notas'
            $descricao,
            'notas',
            null,
            $dados_adicionais
        );
    }

    /**
     * Busca logs por tipo de ação
     * 
     * @param string $tipo Tipo de ação a buscar
     * @param int $limit Limite de registros
     * @param int $offset Offset para paginação
     * @return array Registros encontrados
     */
    public function buscar_por_tipo($tipo, $limit = 50, $offset = 0) {
        $this->db->where('action', $tipo);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get($this->table)->result();
    }

    /**
     * Busca logs relacionados a um módulo específico
     * 
     * @param string $modulo Nome do módulo
     * @param int $record_id ID do registro
     * @param int $limit Limite de registros
     * @return array Registros encontrados
     */
    public function buscar_por_entidade($modulo, $record_id = null, $limit = 20) {
        $this->db->where('module', $modulo);
        if ($record_id) {
            $this->db->where('record_id', $record_id);
        }
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get($this->table)->result();
    }

    /**
     * Busca logs por batch_id de importação
     * 
     * @param int $batch_id ID do lote de importação
     * @return array Registros encontrados
     */
    public function buscar_por_batch($batch_id) {
        $this->db->where('action', 'import');
        $this->db->like('data_after', '"batch_id":"' . $batch_id . '"', 'both');
        $this->db->or_like('data_after', '"batch_id":' . $batch_id . ',', 'both');
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get($this->table)->result();
    }
    
    /**
     * Busca logs por tipos específicos com filtros
     * 
     * @param array $tipos Tipos de log a buscar
     * @param array $filtros Filtros a aplicar
     * @param int $limit Limite de registros
     * @param int $offset Offset para paginação
     * @return array Registros encontrados
     */
    public function buscar_por_tipos($tipos, $filtros = [], $limit = 50, $offset = 0) {
        if (!empty($tipos)) {
            $this->db->where_in('action', $tipos);
        }
        
        // Aplicar filtros
        if (isset($filtros['batch_id']) && !empty($filtros['batch_id'])) {
            $this->db->like('data_after', '"batch_id":"' . $filtros['batch_id'] . '"', 'both');
            $this->db->or_like('data_after', '"batch_id":' . $filtros['batch_id'] . ',', 'both');
        }
        
        if (isset($filtros['data_inicio']) && !empty($filtros['data_inicio'])) {
            $this->db->where('DATE(created_at) >=', $filtros['data_inicio']);
        }
        
        if (isset($filtros['data_fim']) && !empty($filtros['data_fim'])) {
            $this->db->where('DATE(created_at) <=', $filtros['data_fim']);
        }
        
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get($this->table)->result();
    }
    
    /**
     * Conta o número de logs por tipos específicos com filtros
     * 
     * @param array $tipos Tipos de log a contar
     * @param array $filtros Filtros a aplicar
     * @return int Total de registros
     */
    public function contar_logs_por_tipos($tipos, $filtros = []) {
        if (!empty($tipos)) {
            $this->db->where_in('action', $tipos);
        }
        
        // Aplicar filtros
        if (isset($filtros['batch_id']) && !empty($filtros['batch_id'])) {
            $this->db->like('data_after', '"batch_id":"' . $filtros['batch_id'] . '"', 'both');
            $this->db->or_like('data_after', '"batch_id":' . $filtros['batch_id'] . ',', 'both');
        }
        
        if (isset($filtros['data_inicio']) && !empty($filtros['data_inicio'])) {
            $this->db->where('DATE(created_at) >=', $filtros['data_inicio']);
        }
        
        if (isset($filtros['data_fim']) && !empty($filtros['data_fim'])) {
            $this->db->where('DATE(created_at) <=', $filtros['data_fim']);
        }
        
        return $this->db->count_all_results($this->table);
    }
    
    /**
     * Busca um log específico pelo ID
     * 
     * @param int $id ID do log
     * @return object Log encontrado ou null
     */
    public function get_by_id($id) {
        $this->db->select($this->table.'.*, users.name as user_name');
        $this->db->from($this->table);
        $this->db->join('users', 'users.id = '.$this->table.'.user_id', 'left');
        $this->db->where($this->table.'.id', $id);
        return $this->db->get()->row();
    }
    
    /**
     * Retorna os logs mais recentes
     * 
     * @param int $limit Quantidade de logs a retornar
     * @return array Logs mais recentes
     */
    public function get_recent_logs($limit = 5) {
        $this->db->select($this->table.'.*, users.name as user_name');
        $this->db->from($this->table);
        $this->db->join('users', 'users.id = '.$this->table.'.user_id', 'left');
        $this->db->order_by($this->table.'.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }
    
    /**
     * Busca logs com base nos filtros aplicados
     * 
     * @param array $filters Filtros a aplicar
     * @param int $limit Limite de registros
     * @param int $offset Offset para paginação
     * @return array Registros encontrados
     */
    public function get_logs($filters = [], $limit = 50, $offset = 0) {
        // Aplicar filtros
        if (isset($filters['user_id']) && !empty($filters['user_id'])) {
            $this->db->where('user_id', $filters['user_id']);
        }
        
        if (isset($filters['module']) && !empty($filters['module'])) {
            $this->db->where('module', $filters['module']);
        }
        
        if (isset($filters['action']) && !empty($filters['action'])) {
            $this->db->where('action', $filters['action']);
        }
        
        if (isset($filters['tipo']) && !empty($filters['tipo'])) {
            $this->db->where('action', $filters['tipo']);
        }
        
        if (isset($filters['date_start']) && !empty($filters['date_start'])) {
            $this->db->where('DATE(created_at) >=', $filters['date_start']);
        }
        
        if (isset($filters['date_end']) && !empty($filters['date_end'])) {
            $this->db->where('DATE(created_at) <=', $filters['date_end']);
        }
        
        if (isset($filters['search']) && !empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('description', $filters['search']);
            $this->db->or_like('data_after', $filters['search']);
            $this->db->group_end();
        }
        
        if (isset($filters['batch_id']) && !empty($filters['batch_id'])) {
            $this->db->like('data_after', '"batch_id":"' . $filters['batch_id'] . '"', 'both');
            $this->db->or_like('data_after', '"batch_id":' . $filters['batch_id'] . ',', 'both');
        }
        
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get($this->table)->result();
    }

    /**
     * Adiciona um log conforme estrutura existente no sistema
     * 
     * @param string $action Tipo da ação
     * @param string $module Módulo afetado
     * @param int $record_id ID do registro (opcional)
     * @param string $description Descrição da ação
     * @param array $data_before Dados antes da ação (opcional)
     * @param array $data_after Dados após a ação (opcional)
     * @return int ID do log inserido
     */
    public function add_log($action, $module, $record_id = null, $description = '', $data_before = null, $data_after = null) {
        $data = [
            'user_id' => $this->session->userdata('user_id'),
            'action' => $action,
            'module' => $module,
            'record_id' => $record_id,
            'description' => $description,
            'data_before' => $data_before ? json_encode($data_before) : null,
            'data_after' => $data_after ? json_encode($data_after) : null,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    
    /**
     * Conta o número total de logs com base nos filtros
     * 
     * @param array $filters Filtros a aplicar
     * @return int Total de registros
     */
    public function count_logs($filters = []) {
        // Aplicar filtros
        if (isset($filters['user_id']) && !empty($filters['user_id'])) {
            $this->db->where('user_id', $filters['user_id']);
        }
        
        if (isset($filters['module']) && !empty($filters['module'])) {
            $this->db->where('module', $filters['module']);
        }
        
        if (isset($filters['action']) && !empty($filters['action'])) {
            $this->db->where('action', $filters['action']);
        }
        
        if (isset($filters['tipo']) && !empty($filters['tipo'])) {
            $this->db->where('action', $filters['tipo']);
        }
        
        if (isset($filters['date_start']) && !empty($filters['date_start'])) {
            $this->db->where('DATE(created_at) >=', $filters['date_start']);
        }
        
        if (isset($filters['date_end']) && !empty($filters['date_end'])) {
            $this->db->where('DATE(created_at) <=', $filters['date_end']);
        }
        
        if (isset($filters['search']) && !empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('description', $filters['search']);
            $this->db->or_like('data_after', $filters['search']);
            $this->db->group_end();
        }
        
        if (isset($filters['batch_id']) && !empty($filters['batch_id'])) {
            $this->db->like('data_after', '"batch_id":"' . $filters['batch_id'] . '"', 'both');
            $this->db->or_like('data_after', '"batch_id":' . $filters['batch_id'] . ',', 'both');
        }
        
        return $this->db->count_all_results($this->table);
    }
}
