<?php
/**
 * Project Model
 */

class Project extends Model {
    protected $table = 'projects';
    protected $fillable = [
        'name', 'description', 'status', 'budget', 
        'start_date', 'end_date', 'project_manager_id', 'client_id'
    ];
    
    public function getAllWithRelations() {
        $sql = "SELECT p.*, 
                pm.name as project_manager_name,
                GROUP_CONCAT(DISTINCT CONCAT(pu.user_id, ':', u.name, ':', pu.role) SEPARATOR '|') as assigned_users
                FROM projects p
                LEFT JOIN users pm ON p.project_manager_id = pm.id
                LEFT JOIN project_users pu ON p.id = pu.project_id
                LEFT JOIN users u ON pu.user_id = u.id
                GROUP BY p.id
                ORDER BY p.created_at DESC";
        return $this->db->fetchAll($sql);
    }
    
    public function findWithRelations($id) {
        $sql = "SELECT p.*, 
                pm.name as project_manager_name
                FROM projects p
                LEFT JOIN users pm ON p.project_manager_id = pm.id
                WHERE p.id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    public function getProjectUsers($projectId) {
        $sql = "SELECT pu.*, u.name as user_name, u.email as user_email, r.display_name as user_role_name
                FROM project_users pu
                LEFT JOIN users u ON pu.user_id = u.id
                LEFT JOIN roles r ON u.role_id = r.id
                WHERE pu.project_id = ?
                ORDER BY u.name";
        return $this->db->fetchAll($sql, [$projectId]);
    }
    
    public function getUserProjects($userId) {
        $sql = "SELECT p.*, pm.name as project_manager_name, pu.role as user_role
                FROM projects p
                LEFT JOIN users pm ON p.project_manager_id = pm.id
                LEFT JOIN project_users pu ON p.id = pu.project_id
                WHERE pu.user_id = ? OR p.project_manager_id = ?
                ORDER BY p.created_at DESC";
        return $this->db->fetchAll($sql, [$userId, $userId]);
    }
    
    public function assignUsers($projectId, $userIds) {
        // First, remove existing assignments
        $sql = "DELETE FROM project_users WHERE project_id = ?";
        $this->db->query($sql, [$projectId]);
        
        // Then add new assignments
        if (!empty($userIds)) {
            foreach ($userIds as $userId) {
                $sql = "INSERT INTO project_users (project_id, user_id) VALUES (?, ?)";
                $this->db->query($sql, [$projectId, $userId]);
            }
        }
        return true;
    }
    
    public function assignUser($projectId, $userId, $role = 'developer') {
        $sql = "INSERT INTO project_users (project_id, user_id, role) VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE role = VALUES(role)";
        return $this->db->query($sql, [$projectId, $userId, $role]);
    }
    
    public function removeUser($projectId, $userId) {
        $sql = "DELETE FROM project_users WHERE project_id = ? AND user_id = ?";
        return $this->db->query($sql, [$projectId, $userId]);
    }
    
    public function getByStatus($status) {
        return $this->where('status', $status);
    }
    
    public function getActiveProjects() {
        $sql = "SELECT * FROM {$this->table} WHERE status IN ('planning', 'in_progress')";
        return $this->db->fetchAll($sql);
    }
}
