<?php

class DailyReport extends Model
{
    protected $table = 'daily_reports';

    public function getAll()
    {
        $sql = "SELECT dr.*, u.name as user_name 
                FROM daily_reports dr 
                LEFT JOIN users u ON dr.user_id = u.id 
                ORDER BY dr.report_date DESC, dr.created_at DESC";
        return $this->db->fetchAll($sql);
    }

    public function getAllWithUser()
    {
        return $this->getAll(); // Same as getAll() since it already includes user
    }

    public function find($id)
    {
        $sql = "SELECT dr.*, u.name as user_name 
                FROM daily_reports dr 
                LEFT JOIN users u ON dr.user_id = u.id 
                WHERE dr.id = ?";
        return $this->db->fetch($sql, [$id]);
    }

    public function getByUserId($userId)
    {
        $sql = "SELECT dr.*, u.name as user_name 
                FROM daily_reports dr 
                LEFT JOIN users u ON dr.user_id = u.id 
                WHERE dr.user_id = ? 
                ORDER BY dr.report_date DESC, dr.created_at DESC";
        return $this->db->fetchAll($sql, [$userId]);
    }

    public function getByDateRange($startDate, $endDate, $userId = null)
    {
        $sql = "SELECT dr.*, u.name as user_name 
                FROM daily_reports dr 
                LEFT JOIN users u ON dr.user_id = u.id 
                WHERE dr.report_date >= ? AND dr.report_date <= ?";
        $params = [$startDate, $endDate];
        
        if ($userId) {
            $sql .= " AND dr.user_id = ?";
            $params[] = $userId;
        }
        
        $sql .= " ORDER BY dr.report_date DESC, dr.created_at DESC";
        return $this->db->fetchAll($sql, $params);
    }

    public function getTotalHoursWorked($userId, $startDate = null, $endDate = null)
    {
        $sql = "SELECT SUM(hours_worked) as total_hours FROM daily_reports WHERE user_id = ?";
        $params = [$userId];
        
        if ($startDate && $endDate) {
            $sql .= " AND report_date >= ? AND report_date <= ?";
            $params[] = $startDate;
            $params[] = $endDate;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['total_hours'] ?? 0;
    }
}
