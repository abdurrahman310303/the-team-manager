<?php

class Payment extends Model
{
    protected $table = 'payments';

    public function getAll()
    {
        $sql = "SELECT p.*, 
                       i.name as investor_name, 
                       r.name as recipient_name 
                FROM payments p 
                LEFT JOIN users i ON p.investor_id = i.id 
                LEFT JOIN users r ON p.recipient_id = r.id 
                ORDER BY p.payment_date DESC, p.created_at DESC";
        return $this->db->fetchAll($sql);
    }
    
    public function getAllWithRelations()
    {
        $sql = "SELECT p.*, 
                       i.name as investor_name, 
                       r.name as recipient_name 
                FROM payments p 
                LEFT JOIN users i ON p.investor_id = i.id 
                LEFT JOIN users r ON p.recipient_id = r.id 
                ORDER BY p.payment_date DESC, p.created_at DESC";
        return $this->db->fetchAll($sql);
    }

    public function find($id)
    {
        $sql = "SELECT p.*, 
                       i.name as investor_name, 
                       r.name as recipient_name 
                FROM payments p 
                LEFT JOIN users i ON p.investor_id = i.id 
                LEFT JOIN users r ON p.recipient_id = r.id 
                WHERE p.id = ?";
        return $this->db->fetch($sql, [$id]);
    }

    public function getByUserId($userId)
    {
        $sql = "SELECT p.*, 
                       i.name as investor_name, 
                       r.name as recipient_name 
                FROM payments p 
                LEFT JOIN users i ON p.investor_id = i.id 
                LEFT JOIN users r ON p.recipient_id = r.id 
                WHERE p.investor_id = ? OR p.recipient_id = ? 
                ORDER BY p.payment_date DESC, p.created_at DESC";
        return $this->db->fetchAll($sql, [$userId, $userId]);
    }

    public function getByInvestorId($investorId)
    {
        $sql = "SELECT p.*, 
                       i.name as investor_name, 
                       r.name as recipient_name 
                FROM payments p 
                LEFT JOIN users i ON p.investor_id = i.id 
                LEFT JOIN users r ON p.recipient_id = r.id 
                WHERE p.investor_id = ? 
                ORDER BY p.payment_date DESC, p.created_at DESC";
        return $this->db->fetchAll($sql, [$investorId]);
    }

    public function getByRecipientId($recipientId)
    {
        $sql = "SELECT p.*, 
                       i.name as investor_name, 
                       r.name as recipient_name 
                FROM payments p 
                LEFT JOIN users i ON p.investor_id = i.id 
                LEFT JOIN users r ON p.recipient_id = r.id 
                WHERE p.recipient_id = ? 
                ORDER BY p.payment_date DESC, p.created_at DESC";
        return $this->db->fetchAll($sql, [$recipientId]);
    }

    public function getByStatus($status)
    {
        $sql = "SELECT p.*, 
                       i.name as investor_name, 
                       r.name as recipient_name 
                FROM payments p 
                LEFT JOIN users i ON p.investor_id = i.id 
                LEFT JOIN users r ON p.recipient_id = r.id 
                WHERE p.status = ? 
                ORDER BY p.payment_date DESC, p.created_at DESC";
        return $this->db->fetchAll($sql, [$status]);
    }

    public function getByPaymentType($paymentType)
    {
        $sql = "SELECT p.*, 
                       i.name as investor_name, 
                       r.name as recipient_name 
                FROM payments p 
                LEFT JOIN users i ON p.investor_id = i.id 
                LEFT JOIN users r ON p.recipient_id = r.id 
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
            $sql .= " AND (investor_id = ? OR recipient_id = ?)";
            $params[] = $userId;
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
            $sql .= " AND (investor_id = ? OR recipient_id = ?)";
            $params[] = $userId;
            $params[] = $userId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['total'] ?? 0;
    }

    public function getTotalPaymentsForUser($userId, $startDate = null, $endDate = null)
    {
        $sql = "SELECT SUM(amount) as total FROM payments WHERE (investor_id = ? OR recipient_id = ?) AND status = 'completed'";
        $params = [$userId, $userId];
        
        if ($startDate && $endDate) {
            $sql .= " AND payment_date >= ? AND payment_date <= ?";
            $params[] = $startDate;
            $params[] = $endDate;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['total'] ?? 0;
    }
}
