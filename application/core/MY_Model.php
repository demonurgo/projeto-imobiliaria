<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base model class with common CRUD operations and batch methods
 */
class MY_Model extends CI_Model {
    
    protected $table = '';
    protected $primary_key = 'id';
    protected $fillable = array();
    protected $relations = array();
    protected $relation_tables = array();
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Get all records
     * 
     * @param string $order_by Field to order by
     * @param string $order Direction (asc or desc)
     * @return array All records
     */
    public function get_all($order_by = NULL, $order = 'asc') {
        if ($order_by) {
            $this->db->order_by($order_by, $order);
        }
        return $this->db->get($this->table)->result_array();
    }
    
    /**
     * Get a single record by primary key
     * 
     * @param int $id Primary key value
     * @return array Single record
     */
    public function get_by_id($id) {
        $this->db->where($this->primary_key, $id);
        return $this->db->get($this->table)->row_array();
    }
    
    /**
     * Insert a new record or update if exists
     * 
     * @param array $data Data to insert/update
     * @param int $id Primary key value (if updating)
     * @return int Inserted ID or TRUE/FALSE if updating
     */
    public function save($data) {
        // Filter data to only include fillable fields
        if (!empty($this->fillable)) {
            $data = array_intersect_key($data, array_flip($this->fillable));
        }
        
        // Check if record exists by checking a unique field
        $exists = false;
        foreach ($this->get_unique_fields() as $field) {
            if (isset($data[$field])) {
                $this->db->where($field, $data[$field]);
                $existing = $this->db->get($this->table)->row_array();
                
                if ($existing) {
                    $exists = true;
                    $id = $existing[$this->primary_key];
                    break;
                }
            }
        }
        
        if ($exists) {
            // Update existing record
            $this->db->where($this->primary_key, $id);
            $this->db->update($this->table, $data);
            return $id;
        } else {
            // Insert new record
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }
    }
    
    /**
     * Update a record
     * 
     * @param int $id Primary key value
     * @param array $data Data to update
     * @return bool Success/failure
     */
    public function update($id, $data) {
        // Filter data to only include fillable fields
        if (!empty($this->fillable)) {
            $data = array_intersect_key($data, array_flip($this->fillable));
        }
        
        $this->db->where($this->primary_key, $id);
        return $this->db->update($this->table, $data);
    }
    
    /**
     * Delete a record
     * 
     * @param int $id Primary key value
     * @return bool Success/failure
     */
    public function delete($id) {
        // Check relations first
        if (!$this->can_delete($id)) {
            return FALSE;
        }
        
        $this->db->where($this->primary_key, $id);
        return $this->db->delete($this->table);
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
        
        // Check if all records can be deleted
        foreach ($ids as $id) {
            if (!$this->can_delete($id)) {
                return FALSE;
            }
        }
        
        $this->db->where_in($this->primary_key, $ids);
        return $this->db->delete($this->table);
    }
    
    /**
     * Check if a record can be deleted based on its relations
     * 
     * @param int $id Primary key value
     * @return bool Whether the record can be deleted
     */
    public function can_delete($id) {
        // Check defined relations
        foreach ($this->relations as $relation => $foreign_key) {
            $this->db->where($foreign_key, $id);
            $count = $this->db->get($relation)->num_rows();
            
            if ($count > 0) {
                return FALSE;
            }
        }
        
        return TRUE;
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
    protected function get_unique_fields() {
        return array();
    }
}
