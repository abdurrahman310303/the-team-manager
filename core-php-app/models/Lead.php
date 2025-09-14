<?php

class Lead extends Model
{
    protected $table = 'leads';

    public function getAll()
    {
        $sql = "SELECT l.*, u.name as assigned_name 
                FROM leads l 
                LEFT JOIN users u ON l.assigned_to = u.id 
                ORDER BY l.created_at DESC";
        return $this->db->fetchAll($sql);
    }

    public function getAllWithAssignedUser()
    {
        return $this->getAll(); // Same as getAll() since it already includes assigned user
    }

    public function find($id)
    {
        $sql = "SELECT l.*, u.name as assigned_name 
                FROM leads l 
                LEFT JOIN users u ON l.assigned_to = u.id 
                WHERE l.id = ?";
        return $this->db->fetch($sql, [$id]);
    }

    public function getByStatus($status)
    {
        $sql = "SELECT l.*, u.name as assigned_name 
                FROM leads l 
                LEFT JOIN users u ON l.assigned_to = u.id 
                WHERE l.status = ? 
                ORDER BY l.created_at DESC";
        return $this->db->fetchAll($sql, [$status]);
    }

    public function getByAssignedUser($userId)
    {
        $sql = "SELECT l.*, u.name as assigned_name 
                FROM leads l 
                LEFT JOIN users u ON l.assigned_to = u.id 
                WHERE l.assigned_to = ? 
                ORDER BY l.created_at DESC";
        return $this->db->fetchAll($sql, [$userId]);
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE leads SET status = ? WHERE id = ?";
        return $this->db->query($sql, [$status, $id]);
    }
}
