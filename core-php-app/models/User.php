<?php
/**
 * User Model
 */

class User extends Model {
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password', 'role_id'];
    
    public function findByEmail($email) {
        $sql = "SELECT u.*, r.name as role_name, r.display_name as role_display_name 
                FROM users u 
                LEFT JOIN roles r ON u.role_id = r.id 
                WHERE u.email = ?";
        return $this->db->fetch($sql, [$email]);
    }
    
    public function findWithRole($id) {
        $sql = "SELECT u.*, r.name as role_name, r.display_name as role_display_name 
                FROM users u 
                LEFT JOIN roles r ON u.role_id = r.id 
                WHERE u.id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    public function hasRole($role) {
        $user = Session::getUser();
        return $user && $user['role_name'] === $role;
    }
    
    public static function isAdmin() {
        $user = Session::getUser();
        return $user && $user['role_name'] === 'admin';
    }
    
    public static function isDeveloper() {
        $user = Session::getUser();
        return $user && $user['role_name'] === 'developer';
    }
    
    public static function isInvestor() {
        $user = Session::getUser();
        return $user && $user['role_name'] === 'investor';
    }
    
    public static function isBD() {
        $user = Session::getUser();
        return $user && $user['role_name'] === 'bd';
    }
    
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    public function createUser($data) {
        $data['password'] = $this->hashPassword($data['password']);
        return $this->create($data);
    }
    
    public function getUsersByRole($roleName) {
        $sql = "SELECT u.*, r.name as role_name, r.display_name as role_display_name 
                FROM users u 
                LEFT JOIN roles r ON u.role_id = r.id 
                WHERE r.name = ? 
                ORDER BY u.name";
        return $this->db->fetchAll($sql, [$roleName]);
    }
    
    public function getAll() {
        $sql = "SELECT u.*, r.name as role_name, r.display_name as role_display_name 
                FROM users u 
                LEFT JOIN roles r ON u.role_id = r.id 
                ORDER BY u.name";
        return $this->db->fetchAll($sql);
    }
    
    public function getAllWithRoles() {
        return $this->getAll();
    }
    
    public function getAllRoles() {
        $sql = "SELECT * FROM roles ORDER BY display_name";
        return $this->db->fetchAll($sql);
    }
    
    public function findRole($id) {
        $sql = "SELECT * FROM roles WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    public function getUsersByRoleId($roleId) {
        $sql = "SELECT u.*, r.name as role_name, r.display_name as role_display_name 
                FROM users u 
                LEFT JOIN roles r ON u.role_id = r.id 
                WHERE u.role_id = ? 
                ORDER BY u.name";
        return $this->db->fetchAll($sql, [$roleId]);
    }
    
    public function createRole($data) {
        $sql = "INSERT INTO roles (name, display_name, description) VALUES (?, ?, ?)";
        return $this->db->query($sql, [
            $data['name'], 
            $data['display_name'], 
            $data['description'] ?? ''
        ]);
    }
    
    public function updateRole($id, $data) {
        $sql = "UPDATE roles SET name = ?, display_name = ?, description = ? WHERE id = ?";
        return $this->db->query($sql, [
            $data['name'], 
            $data['display_name'], 
            $data['description'] ?? '',
            $id
        ]);
    }
    
    public function deleteRole($id) {
        $sql = "DELETE FROM roles WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
}
