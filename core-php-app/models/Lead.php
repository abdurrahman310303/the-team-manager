<?php

class Lead extends Model
{
    protected $table = 'leads';
    protected $fillable = [
        'company_name',
        'contact_person', 
        'email',
        'phone',
        'description',
        'status',
        'source',
        'estimated_value',
        'assigned_to',
        'project_id',
        'last_contact_date',
        'notes'
    ];

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

    public function findWithAssignedUser($id)
    {
        return $this->find($id); // Same as find() since it already includes assigned user
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

    public function getByAssignedOrCreatedBy($userId)
    {
        $sql = "SELECT l.*, u.name as assigned_name 
                FROM leads l 
                LEFT JOIN users u ON l.assigned_to = u.id 
                WHERE l.assigned_to = ? OR l.created_by = ? 
                ORDER BY l.created_at DESC";
        return $this->db->fetchAll($sql, [$userId, $userId]);
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE leads SET status = ? WHERE id = ?";
        return $this->db->query($sql, [$status, $id]);
    }

    public function create($data)
    {
        if (parent::create($data)) {
            return $this->db->lastInsertId();
        }
        return false;
    }
}
