<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nota_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function get_all() {
        $this->db->select('notas.*, 
                          prestadores.razao_social as prestador_nome, 
                          tomadores.razao_social as tomador_nome,
                          inquilinos.nome as inquilino_nome');
        $this->db->from('notas');
        $this->db->join('prestadores', 'prestadores.id = notas.prestador_id', 'left');
        $this->db->join('tomadores', 'tomadores.id = notas.tomador_id', 'left');
        $this->db->join('inquilinos', 'inquilinos.id = notas.inquilino_id', 'left');
        $this->db->order_by('notas.data_emissao', 'DESC');
        
        return $this->db->get()->result_array();
    }
    
    public function get_by_id($id) {
        $this->db->select('notas.*, 
                          prestadores.razao_social as prestador_nome, 
                          tomadores.razao_social as tomador_nome,
                          inquilinos.nome as inquilino_nome');
        $this->db->from('notas');
        $this->db->join('prestadores', 'prestadores.id = notas.prestador_id', 'left');
        $this->db->join('tomadores', 'tomadores.id = notas.tomador_id', 'left');
        $this->db->join('inquilinos', 'inquilinos.id = notas.inquilino_id', 'left');
        $this->db->where('notas.id', $id);
        
        return $this->db->get()->row_array();
    }
    
    public function get_by_batch($batch_id) {
        $this->db->select('notas.*, 
                          prestadores.razao_social as prestador_nome, 
                          tomadores.razao_social as tomador_nome,
                          inquilinos.nome as inquilino_nome');
        $this->db->from('notas');
        $this->db->join('prestadores', 'prestadores.id = notas.prestador_id', 'left');
        $this->db->join('tomadores', 'tomadores.id = notas.tomador_id', 'left');
        $this->db->join('inquilinos', 'inquilinos.id = notas.inquilino_id', 'left');
        $this->db->where('notas.batch_id', $batch_id);
        $this->db->order_by('notas.numero', 'ASC');
        
        return $this->db->get()->result_array();
    }
    
    public function get_by_prestador($prestador_id) {
        $this->db->select('notas.*, 
                          prestadores.razao_social as prestador_nome, 
                          tomadores.razao_social as tomador_nome,
                          inquilinos.nome as inquilino_nome');
        $this->db->from('notas');
        $this->db->join('prestadores', 'prestadores.id = notas.prestador_id', 'left');
        $this->db->join('tomadores', 'tomadores.id = notas.tomador_id', 'left');
        $this->db->join('inquilinos', 'inquilinos.id = notas.inquilino_id', 'left');
        $this->db->where('notas.prestador_id', $prestador_id);
        $this->db->order_by('notas.data_emissao', 'DESC');
        
        return $this->db->get()->result_array();
    }
    
    public function get_by_inquilino($inquilino_id) {
        $this->db->select('notas.*, 
                          prestadores.razao_social as prestador_nome, 
                          tomadores.razao_social as tomador_nome');
        $this->db->from('notas');
        $this->db->join('prestadores', 'prestadores.id = notas.prestador_id', 'left');
        $this->db->join('tomadores', 'tomadores.id = notas.tomador_id', 'left');
        $this->db->where('notas.inquilino_id', $inquilino_id);
        $this->db->order_by('notas.data_emissao', 'DESC');
        
        return $this->db->get()->result_array();
    }
    
    public function get_by_imovel($imovel_id) {
        $this->db->select('notas.*, 
                          prestadores.razao_social as prestador_nome, 
                          tomadores.razao_social as tomador_nome,
                          inquilinos.nome as inquilino_nome');
        $this->db->from('notas');
        $this->db->join('prestadores', 'prestadores.id = notas.prestador_id', 'left');
        $this->db->join('tomadores', 'tomadores.id = notas.tomador_id', 'left');
        $this->db->join('inquilinos', 'inquilinos.id = notas.inquilino_id', 'left');
        $this->db->where('notas.imovel_id', $imovel_id);
        $this->db->order_by('notas.data_emissao', 'DESC');
        
        return $this->db->get()->result_array();
    }
    
    public function save($data) {
        // Verificar se a nota já existe pelo número e código de verificação (identificadores únicos da nota)
        $this->db->where('numero', $data['numero']);
        $this->db->where('codigo_verificacao', $data['codigo_verificacao']);
        $query = $this->db->get('notas');
        
        if ($query->num_rows() > 0) {
            // Nota já existe, retornar false para indicar que não foi inserida
            return false;
        } else {
            // Inserir nova nota
            $this->db->insert('notas', $data);
            return $this->db->insert_id();
        }
    }
    
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('notas', $data);
    }
    
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('notas');
    }
    
    public function get_by_year($year) {
        $this->db->select('notas.*, 
                          prestadores.razao_social as prestador_nome, 
                          tomadores.razao_social as tomador_nome,
                          inquilinos.nome as inquilino_nome');
        $this->db->from('notas');
        $this->db->join('prestadores', 'prestadores.id = notas.prestador_id', 'left');
        $this->db->join('tomadores', 'tomadores.id = notas.tomador_id', 'left');
        $this->db->join('inquilinos', 'inquilinos.id = notas.inquilino_id', 'left');
        $this->db->where('YEAR(notas.competencia)', $year);
        $this->db->order_by('notas.competencia', 'ASC');
        
        return $this->db->get()->result_array();
    }
    
    public function get_filtered($filters = array()) {
        $this->db->select('notas.*, 
                          prestadores.razao_social as prestador_nome, 
                          tomadores.razao_social as tomador_nome,
                          inquilinos.nome as inquilino_nome');
        $this->db->from('notas');
        $this->db->join('prestadores', 'prestadores.id = notas.prestador_id', 'left');
        $this->db->join('tomadores', 'tomadores.id = notas.tomador_id', 'left');
        $this->db->join('inquilinos', 'inquilinos.id = notas.inquilino_id', 'left');
        
        // Aplicar filtros
        if (!empty($filters)) {
            // Filtrar por número
            if (isset($filters['numero'])) {
                $this->db->like('notas.numero', $filters['numero']);
            }
            
            // Filtrar por data de emissão
            if (isset($filters['data_emissao'])) {
                $date = date('Y-m-d', strtotime($filters['data_emissao']));
                $this->db->where('DATE(notas.data_emissao)', $date);
            }
            
            // Filtrar por prestador
            if (isset($filters['prestador'])) {
                $this->db->like('prestadores.razao_social', $filters['prestador']);
            }
            
            // Filtrar por tomador
            if (isset($filters['tomador'])) {
                $this->db->like('tomadores.razao_social', $filters['tomador']);
            }
            
            // Filtrar por valor
            if (isset($filters['valor_servicos'])) {
                // Remover formatação de valor para comparar com o banco
                $valor = str_replace('.', '', $filters['valor_servicos']);
                $valor = str_replace(',', '.', $valor);
                $this->db->where('notas.valor_servicos', $valor);
            }
            
            // Filtrar por inquilino
            if (isset($filters['inquilino'])) {
                $this->db->like('inquilinos.nome', $filters['inquilino']);
            }
            
            // Filtrar por status
            if (isset($filters['status'])) {
                $this->db->where('notas.status', $filters['status']);
            }
            
            // Filtrar por editado manualmente
            if (isset($filters['editado_manualmente'])) {
                $this->db->where('notas.editado_manualmente', $filters['editado_manualmente']);
            }
        }
        
        $this->db->order_by('notas.data_emissao', 'DESC');
        
        return $this->db->get()->result_array();
    }
}
