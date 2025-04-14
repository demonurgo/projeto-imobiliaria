<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prestador_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function get_all() {
        $this->db->order_by('razao_social', 'ASC');
        return $this->db->get('prestadores')->result_array();
    }
    
    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('prestadores')->row_array();
    }
    
    public function get_by_cnpj($cnpj) {
        $this->db->where('cnpj', $cnpj);
        return $this->db->get('prestadores')->row_array();
    }
    
    public function save($data) {
        // Remove caracteres não numéricos do CNPJ
        if (isset($data['cnpj'])) {
            $data['cnpj'] = preg_replace('/[^0-9]/', '', $data['cnpj']);
        }
        
        // Verificar se o prestador já existe
        $this->db->where('cnpj', $data['cnpj']);
        $query = $this->db->get('prestadores');
        
        if ($query->num_rows() > 0) {
            // Atualizar prestador existente
            $existing = $query->row_array();
            $this->db->where('id', $existing['id']);
            $this->db->update('prestadores', $data);
            return $existing['id'];
        } else {
            // Inserir novo prestador
            $this->db->insert('prestadores', $data);
            return $this->db->insert_id();
        }
    }
    
    public function update($id, $data) {
        // Remove caracteres não numéricos do CNPJ
        if (isset($data['cnpj'])) {
            $data['cnpj'] = preg_replace('/[^0-9]/', '', $data['cnpj']);
        }
        
        $this->db->where('id', $id);
        return $this->db->update('prestadores', $data);
    }
    
    public function delete($id) {
        // Verificar se há notas associadas a este prestador
        $this->db->where('prestador_id', $id);
        $notas = $this->db->get('notas')->num_rows();
        
        if ($notas > 0) {
            return FALSE; // Não pode excluir prestador com notas associadas
        }
        
        $this->db->where('id', $id);
        return $this->db->delete('prestadores');
    }
}
