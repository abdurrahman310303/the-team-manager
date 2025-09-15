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
        
        if (empty($data)) {
            throw new Exception("No valid data to insert");
        }
        
        $columns = implode(',', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        
        try {
            // Use the Database wrapper's query method
            $stmt = $this->db->query($sql, array_values($data));
            
            if (!$stmt) {
                throw new Exception("Failed to execute insert statement");
            }
            
            // Get the last insert ID using Database wrapper
            $insertId = $this->db->lastInsertId();
            
            if (!$insertId || $insertId == '0' || $insertId === '0') {
                throw new Exception("Failed to get last insert ID. Insert may have failed.");
            }
            
            // Ensure we return a proper integer
            return (int) $insertId;
            
        } catch (PDOException $e) {
            throw new Exception("Database insert failed: " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("Insert operation failed: " . $e->getMessage());
        }
    }
    
    public function update($id, $data) {
        $data = $this->filterFillable($data);
        $setParts = [];
        $values = [];
        
        foreach (array_keys($data) as $column) {
            $setParts[] = "{$column} = ?";
        }
        
        $setClause = implode(', ', $setParts);
        $values = array_values($data);
        $values[] = $id; // Add ID at the end
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE id = ?";
        return $this->db->query($sql, $values);
    }
    
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
    
    protected function filterFillable($data) {
        return array_intersect_key($data, array_flip($this->fillable));
    }
}
