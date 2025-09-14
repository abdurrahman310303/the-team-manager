<?php

class Payment extends Model
{
    protected $table = 'payments';

    public function getAll()
    {
        $sql = "SELECT p.*, u.name as user_name 
                FROM payments p 
                LEFT JOIN users u ON p.user_id = u.id 
                ORDER BY p.payment_date DESC, p.created_at DESC";
        return $this->db->fetchAll($sql);
    }

    public function find($id)
    {
        $sql = "SELECT p.*, u.name as user_name 
                FROM payments p 
                LEFT JOIN users u ON p.user_id = u.id 
                WHERE p.id = ?";
        return $this->db->fetch($sql, [$id]);
    }

    public function getByUserId($userId)
    {
        $sql = "SELECT p.*, u.name as user_name 
                FROM payments p 
                LEFT JOIN users u ON p.user_id = u.id 
                WHERE p.user_id = ? 
                ORDER BY p.payment_date DESC, p.created_at DESC";
        return $this->db->fetchAll($sql, [$userId]);
    }

    public function getByStatus($status)
    {
        $sql = "SELECT p.*, u.name as user_name 
                FROM payments p 
                LEFT JOIN users u ON p.user_id = u.id 
                WHERE p.status = ? 
                ORDER BY p.payment_date DESC, p.created_at DESC";
        return $this->db->fetchAll($sql, [$status]);
    }

    public function getByPaymentType($paymentType)
    {
        $sql = "SELECT p.*, u.name as user_name 
                FROM payments p 
                LEFT JOIN users u ON p.user_id = u.id 
                WHERE p.payment_type = ? 
                ORDER BY p.payment_date DESC, p.created_at DESC";
        return $this->db->fetchAll($sql, [$paymentType]);
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE payments SET status = ? WHERE id = ?";
        return $this->db->query($sql, [$status, $id]);
    }

    public function getTotalByType($paymentType, $userId = null, $status = null)
    {
        $sql = "SELECT SUM(amount) as total FROM payments WHERE payment_type = ?";
        $params = [$paymentType];
        
        if ($userId) {
            $sql .= " AND user_id = ?";
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
        $sql = "SELECT SUM(amount) as total FROM payments WHERE status = ?";
        $params = [$status];
        
        if ($userId) {
            $sql .= " AND user_id = ?";
            $params[] = $userId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['total'] ?? 0;
    }

    public function getTotalPaymentsForUser($userId, $startDate = null, $endDate = null)
    {
        $sql = "SELECT SUM(amount) as total FROM payments WHERE user_id = ? AND status = 'completed'";
        $params = [$userId];
        
        if ($startDate && $endDate) {
            $sql .= " AND payment_date >= ? AND payment_date <= ?";
            $params[] = $startDate;
            $params[] = $endDate;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['total'] ?? 0;
    }
}
