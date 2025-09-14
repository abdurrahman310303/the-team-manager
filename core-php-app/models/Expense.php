<?php

class Expense extends Model
{
    protected $table = 'expenses';

    public function getAll()
    {
        $sql = "SELECT e.*, u.name as user_name 
                FROM expenses e 
                LEFT JOIN users u ON e.added_by = u.id 
                ORDER BY e.expense_date DESC, e.created_at DESC";
        return $this->db->fetchAll($sql);
    }

    public function getAllWithUsers()
    {
        return $this->getAll(); // Same as getAll() since it already includes user
    }

    public function find($id)
    {
        $sql = "SELECT e.*, u.name as user_name 
                FROM expenses e 
                LEFT JOIN users u ON e.added_by = u.id 
                WHERE e.id = ?";
        return $this->db->fetch($sql, [$id]);
    }

    public function getByUserId($userId)
    {
        $sql = "SELECT e.*, u.name as user_name 
                FROM expenses e 
                LEFT JOIN users u ON e.added_by = u.id 
                WHERE e.added_by = ? 
                ORDER BY e.expense_date DESC, e.created_at DESC";
        return $this->db->fetchAll($sql, [$userId]);
    }

    public function getByStatus($status)
    {
        $sql = "SELECT e.*, u.name as user_name 
                FROM expenses e 
                LEFT JOIN users u ON e.added_by = u.id 
                WHERE e.status = ? 
                ORDER BY e.expense_date DESC, e.created_at DESC";
        return $this->db->fetchAll($sql, [$status]);
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE expenses SET status = ? WHERE id = ?";
        return $this->db->query($sql, [$status, $id]);
    }

    public function getTotalByCategory($category, $userId = null, $status = null)
    {
        $sql = "SELECT SUM(amount) as total FROM expenses WHERE category = ?";
        $params = [$category];
        
        if ($userId) {
            $sql .= " AND added_by = ?";
            $params[] = $userId;
        }
        
        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['total'] ?? 0;
    }

    public function getTotalByStatus($status, $userId = null)
    {
        $sql = "SELECT SUM(amount) as total FROM expenses WHERE status = ?";
        $params = [$status];
        
        if ($userId) {
            $sql .= " AND added_by = ?";
            $params[] = $userId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['total'] ?? 0;
    }
}
