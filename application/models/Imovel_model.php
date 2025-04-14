<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Imovel_model extends MY_Model {

    protected $table = 'imoveis';
    protected $primary_key = 'id';
    protected $fillable = array(
        'endereco', 'numero', 'complemento', 'bairro', 'cidade', 'uf', 'cep',
        'referencia', 'codigo_referencia', 'inquilino_id', 'tomador_id', 'valor_aluguel',
        'observacoes'
    );
    protected $relations = array(
        'notas' => 'imovel_id'
    );
    
    public function __construct() {
        parent::__construct();
    }
    
    public function get_all($order_by = NULL, $order = 'asc') {
        // Se a ordenação padrão foi solicitada, usamos a implementação personalizada com joins
        if ($order_by === NULL) {
            $this->db->select($this->table.'.*, 
                           inquilinos.nome as inquilino_nome, 
                           tomadores.razao_social as tomador_nome');
            $this->db->from($this->table);
            $this->db->join('inquilinos', 'inquilinos.id = '.$this->table.'.inquilino_id', 'left');
            $this->db->join('tomadores', 'tomadores.id = '.$this->table.'.tomador_id', 'left');
            $this->db->order_by($this->table.'.endereco', 'ASC');
            
            return $this->db->get()->result_array();
        } else {
            // Caso contrário, usamos a implementação base com a ordenação personalizada
            return parent::get_all($order_by, $order);
        }
    }
    
    public function get_by_id($id) {
        $this->db->select($this->table.'.*, 
                          inquilinos.nome as inquilino_nome,
                          tomadores.razao_social as tomador_nome');
        $this->db->from($this->table);
        $this->db->join('inquilinos', 'inquilinos.id = '.$this->table.'.inquilino_id', 'left');
        $this->db->join('tomadores', 'tomadores.id = '.$this->table.'.tomador_id', 'left');
        $this->db->where($this->table.'.id', $id);
        
        return $this->db->get()->row_array();
    }
    
    public function get_by_endereco($endereco) {
        $this->db->like('endereco', $endereco);
        return $this->db->get($this->table)->result_array();
    }
    
    public function get_by_inquilino($inquilino_id) {
        $this->db->where('inquilino_id', $inquilino_id);
        return $this->db->get($this->table)->result_array();
    }
    
    public function get_by_tomador($tomador_id) {
        $this->db->where('tomador_id', $tomador_id);
        return $this->db->get($this->table)->result_array();
    }
    
    /**
     * Delete multiple records
     * 
     * @param array $ids Primary key values
     * @return bool Success/failure
     */
    public function delete_batch($ids) {
        if (empty($ids)) {
            return FALSE;
        }
        
        // Verificar se algum dos imóveis tem notas associadas
        $this->db->where_in('imovel_id', $ids);
        $notas = $this->db->get('notas')->num_rows();
        
        if ($notas > 0) {
            return FALSE; // Não pode excluir imóveis com notas associadas
        }
        
        $this->db->where_in('id', $ids);
        return $this->db->delete($this->table);
    }
    
    /**
     * Check which records among a batch can't be deleted due to relations
     * 
     * @param array $ids Primary key values
     * @return array IDs that cannot be deleted
     */
    public function check_batch_relations($ids) {
        if (empty($ids)) {
            return array();
        }
        
        $locked_ids = array();
        
        foreach ($ids as $id) {
            $this->db->where('imovel_id', $id);
            $notas = $this->db->get('notas')->num_rows();
            
            if ($notas > 0) {
                $locked_ids[] = $id;
            }
        }
        
        return $locked_ids;
    }
    
    /**
     * Get list of fields that should be unique
     * 
     * @return array List of unique fields
     */
    protected function get_unique_fields() {
        return array('codigo_referencia');
    }
    
    /**
     * Override do método save para verificar duplicação de imóveis pelo endereço completo
     */
    public function save($data) {
        // Verificar se já existe um imóvel com o mesmo endereço
        if (isset($data['endereco']) && !empty($data['endereco'])) {
            $this->db->where('endereco', $data['endereco']);
            
            // Adicionar outros campos do endereço na verificação, se disponíveis
            if (isset($data['numero']) && !empty($data['numero'])) {
                $this->db->where('numero', $data['numero']);
            }
            
            if (isset($data['complemento']) && !empty($data['complemento'])) {
                $this->db->where('complemento', $data['complemento']);
            }
            
            if (isset($data['bairro']) && !empty($data['bairro'])) {
                $this->db->where('bairro', $data['bairro']);
            }
            
            if (isset($data['cidade']) && !empty($data['cidade'])) {
                $this->db->where('cidade', $data['cidade']);
            }
            
            $existing = $this->db->get($this->table)->row_array();
            
            if ($existing) {
                // Imóvel já existe, atualizar valores como inquilino_id e valor_aluguel se necessário
                $update_data = array();
                
                // Só atualizar campos importantes e não vazios
                if (isset($data['inquilino_id']) && !empty($data['inquilino_id'])) {
                    $update_data['inquilino_id'] = $data['inquilino_id'];
                }
                
                if (isset($data['valor_aluguel']) && !empty($data['valor_aluguel'])) {
                    $update_data['valor_aluguel'] = $data['valor_aluguel'];
                }
                
                if (isset($data['tomador_id']) && !empty($data['tomador_id'])) {
                    $update_data['tomador_id'] = $data['tomador_id'];
                }
                
                if (isset($data['observacoes']) && !empty($data['observacoes'])) {
                    // Se já existem observações, apendamos as novas
                    if (!empty($existing['observacoes'])) {
                        $update_data['observacoes'] = $existing['observacoes'] . "\n" . $data['observacoes'];
                    } else {
                        $update_data['observacoes'] = $data['observacoes'];
                    }
                }
                
                // Se temos dados para atualizar
                if (!empty($update_data)) {
                    $this->update($existing['id'], $update_data);
                }
                
                return $existing['id'];
            }
        }
        
        // Se não existir, criar um novo imóvel
        return parent::save($data);
    }
}
