<?php
/**
 * Base Model Class
 */

abstract class Model {
    protected $table;
    protected $fillable = [];
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    public function all() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->fetchAll($sql);
    }
    
    public function where($column, $value) {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = ?";
        return $this->db->fetchAll($sql, [$value]);
    }
    
    public function create($data) {
        $data = $this->filterFillable($data);
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $this->db->query($sql, $data);
        
        return $this->db->lastInsertId();
    }
    
    public function update($id, $data) {
        $data = $this->filterFillable($data);
        $setParts = [];
        
        foreach (array_keys($data) as $column) {
            $setParts[] = "{$column} = :{$column}";
        }
        
        $setClause = implode(', ', $setParts);
        $data['id'] = $id;
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE id = :id";
        return $this->db->query($sql, $data);
    }
    
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
    
    protected function filterFillable($data) {
        return array_intersect_key($data, array_flip($this->fillable));
    }
}
