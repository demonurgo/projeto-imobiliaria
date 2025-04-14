<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tomador_model extends MY_Model
{
    protected $table = 'tomadores';
    protected $primary_key = 'id';
    protected $fillable = array(
        'razao_social', 'cpf_cnpj', 'telefone', 'email', 'endereco', 'numero',
        'complemento', 'bairro', 'cidade', 'uf', 'cep'
    );
    protected $relations = array(
        'notas' => 'tomador_id',
        'imoveis' => 'tomador_id'
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all($order_by = NULL, $order = 'asc') {
        // Usar ordenação padrão por razão social se nenhuma for especificada
        if ($order_by === NULL) {
            $order_by = 'razao_social';
        }
        
        $this->db->order_by($order_by, $order);
        return $this->db->get($this->table)->result_array();
    }

    public function get_by_documento($documento)
    {
        // Documento pode ser CPF ou CNPJ
        $this->db->where('cpf_cnpj', $documento);
        return $this->db->get($this->table)->row_array();
    }

    public function save($data)
    {
        // Combinar CPF ou CNPJ no campo cpf_cnpj
        if (isset($data['cpf']) && !empty($data['cpf'])) {
            $data['cpf_cnpj'] = preg_replace('/[^0-9]/', '', $data['cpf']);
            unset($data['cpf']);
        } elseif (isset($data['cnpj']) && !empty($data['cnpj'])) {
            $data['cpf_cnpj'] = preg_replace('/[^0-9]/', '', $data['cnpj']);
            unset($data['cnpj']);
        }

        // Remover campos que não existem na tabela
        if (isset($data['cpf'])) unset($data['cpf']);
        if (isset($data['cnpj'])) unset($data['cnpj']);
        if (isset($data['tipo_documento'])) unset($data['tipo_documento']);

        return parent::save($data);
    }

    public function update($id, $data)
    {
        // Combinar CPF ou CNPJ no campo cpf_cnpj
        if (isset($data['cpf']) && !empty($data['cpf'])) {
            $data['cpf_cnpj'] = preg_replace('/[^0-9]/', '', $data['cpf']);
            unset($data['cpf']);
        } elseif (isset($data['cnpj']) && !empty($data['cnpj'])) {
            $data['cpf_cnpj'] = preg_replace('/[^0-9]/', '', $data['cnpj']);
            unset($data['cnpj']);
        }

        // Remover campos que não existem na tabela
        if (isset($data['cpf'])) unset($data['cpf']);
        if (isset($data['cnpj'])) unset($data['cnpj']);
        if (isset($data['tipo_documento'])) unset($data['tipo_documento']);

        return parent::update($id, $data);
    }

    /**
     * Check if a record can be deleted based on its relations
     * 
     * @param int $id Primary key value
     * @return bool Whether the record can be deleted
     */
    public function can_delete($id)
    {
        // Verificar notas associadas
        $this->db->where('tomador_id', $id);
        $notas = $this->db->get('notas')->num_rows();
        
        if ($notas > 0) {
            return FALSE;
        }
        
        // Verificar imóveis associados
        $this->db->where('tomador_id', $id);
        $imoveis = $this->db->get('imoveis')->num_rows();
        
        if ($imoveis > 0) {
            return FALSE;
        }
        
        return TRUE;
    }
    
    /**
     * Check which records among a batch can't be deleted due to relations
     * 
     * @param array $ids Primary key values
     * @return array IDs that cannot be deleted
     */
    public function check_batch_relations($ids)
    {
        if (empty($ids)) {
            return array();
        }
        
        $locked_ids = array();
        
        foreach ($ids as $id) {
            if (!$this->can_delete($id)) {
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
    protected function get_unique_fields()
    {
        return array('cpf_cnpj');
    }
}
