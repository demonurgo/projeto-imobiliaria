<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inquilino_model extends MY_Model {

    protected $table = 'inquilinos';
    protected $primary_key = 'id';
    protected $fillable = array(
        'nome', 'cpf_cnpj', 'telefone', 'email', 'endereco', 'observacoes'
    );
    protected $relations = array(
        'notas' => 'inquilino_id',
        'imoveis' => 'inquilino_id'
    );
    
    public function __construct() {
        parent::__construct();
    }
    
    public function get_all($order_by = NULL, $order = 'asc') {
        // Usar ordenação padrão por nome se nenhuma for especificada
        if ($order_by === NULL) {
            $order_by = 'nome';
        }
        
        $this->db->order_by($order_by, $order);
        return $this->db->get($this->table)->result_array();
    }
    
    public function get_by_cpf($cpf) {
        $this->db->where('cpf_cnpj', $cpf);
        return $this->db->get($this->table)->row_array();
    }
    
    public function save($data) {
        // Limpar CPF ou CNPJ para conter apenas números
        if (isset($data['cpf_cnpj'])) {
            $data['cpf_cnpj'] = preg_replace('/[^0-9]/', '', $data['cpf_cnpj']);
        }
        
        // Compatibilidade: Se temos um campo cpf ainda sendo usado, move para cpf_cnpj
        if (isset($data['cpf']) && !isset($data['cpf_cnpj'])) {
            $data['cpf_cnpj'] = preg_replace('/[^0-9]/', '', $data['cpf']);
            unset($data['cpf']); // Remover o campo cpf para evitar confusão
        }
        
        return parent::save($data);
    }
    
    public function update($id, $data) {
        // Limpar CPF/CNPJ para conter apenas números
        if (isset($data['cpf_cnpj'])) {
            $data['cpf_cnpj'] = preg_replace('/[^0-9]/', '', $data['cpf_cnpj']);
        }
        
        // Compatibilidade: Se temos um campo cpf ainda sendo usado, move para cpf_cnpj
        if (isset($data['cpf']) && !isset($data['cpf_cnpj'])) {
            $data['cpf_cnpj'] = preg_replace('/[^0-9]/', '', $data['cpf']);
            unset($data['cpf']); // Remover o campo cpf para evitar confusão
        }
        
        return parent::update($id, $data);
    }
    
    
    /**
     * Get list of fields that should be unique
     * 
     * @return array List of unique fields
     */
    protected function get_unique_fields() {
        return array('cpf_cnpj');
    }
}
