<?php
/**
 * Role Controller - Admin-only role management
 */

require_once __DIR__ . '/../models/User.php';

class RoleController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {
        Auth::requireAdmin();
        
        $roles = $this->userModel->getAllRoles();
        
        $currentPage = 'roles';
        require_once __DIR__ . '/../views/roles/index.php';
    }

    public function show()
    {
        Auth::requireAdmin();
        
        $path = $_SERVER['REQUEST_URI'];
        $id = (int) basename($path);
        
        if (!$id) {
            header('Location: /roles');
            exit;
        }

        $role = $this->userModel->findRole($id);
        if (!$role) {
            Session::flash('error', 'Role not found');
            header('Location: /roles');
            exit;
        }

        // Get users with this role
        $usersWithRole = $this->userModel->getUsersByRoleId($id);

        $currentPage = 'roles';
        require_once __DIR__ . '/../views/roles/show.php';
    }

    public function create()
    {
        Auth::requireAdmin();
        
        $currentPage = 'roles';
        require_once __DIR__ . '/../views/roles/create.php';
    }

    public function store()
    {
        Auth::requireAdmin();

        $required = ['name', 'display_name'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                Session::flash('error', 'Please fill in all required fields');
                header('Location: /roles/create');
                exit;
            }
        }

        $data = [
            'name' => trim($_POST['name']),
            'display_name' => trim($_POST['display_name']),
            'description' => !empty($_POST['description']) ? trim($_POST['description']) : ''
        ];

        $roleId = $this->userModel->createRole($data);
        
        if ($roleId && is_numeric($roleId)) {
            Session::flash('success', 'Role created successfully');
            header('Location: /roles/' . (int)$roleId);
        } else {
            Session::flash('error', 'Error creating role');
            header('Location: /roles/create');
        }
        exit;
    }

    public function edit()
    {
        Auth::requireAdmin();
        
        $path = $_SERVER['REQUEST_URI'];
        $id = (int) basename(dirname($path));
        
        if (!$id) {
            header('Location: /roles');
            exit;
        }

        $role = $this->userModel->findRole($id);
        if (!$role) {
            Session::flash('error', 'Role not found');
            header('Location: /roles');
            exit;
        }

        $currentPage = 'roles';
        require_once __DIR__ . '/../views/roles/edit.php';
    }

    public function update()
    {
        Auth::requireAdmin();
        
        $path = $_SERVER['REQUEST_URI'];
        $segments = explode('/', trim($path, '/'));
        $id = null;
        
        foreach ($segments as $i => $segment) {
            if ($segment === 'roles' && isset($segments[$i + 1]) && is_numeric($segments[$i + 1])) {
                $id = (int) $segments[$i + 1];
                break;
            }
        }
        
        if (!$id) {
            header('Location: /roles');
            exit;
        }

        $required = ['name', 'display_name'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                Session::flash('error', 'Please fill in all required fields');
                header('Location: /roles/' . $id . '/edit');
                exit;
            }
        }

        $data = [
            'name' => trim($_POST['name']),
            'display_name' => trim($_POST['display_name']),
            'description' => !empty($_POST['description']) ? trim($_POST['description']) : ''
        ];

        if ($this->userModel->updateRole($id, $data)) {
            Session::flash('success', 'Role updated successfully');
            header('Location: /roles/' . $id);
        } else {
            Session::flash('error', 'Error updating role');
            header('Location: /roles/' . $id . '/edit');
        }
        exit;
    }

    public function delete()
    {
        Auth::requireAdmin();
        
        $path = $_SERVER['REQUEST_URI'];
        $segments = explode('/', trim($path, '/'));
        $id = null;
        
        foreach ($segments as $i => $segment) {
            if ($segment === 'roles' && isset($segments[$i + 1]) && is_numeric($segments[$i + 1])) {
                $id = (int) $segments[$i + 1];
                break;
            }
        }
        
        if (!$id) {
            header('Location: /roles');
            exit;
        }

        // Check if any users have this role
        $usersWithRole = $this->userModel->getUsersByRoleId($id);
        if (!empty($usersWithRole)) {
            Session::flash('error', 'Cannot delete role that has users assigned to it');
            header('Location: /roles');
            exit;
        }

        if ($this->userModel->deleteRole($id)) {
            Session::flash('success', 'Role deleted successfully');
        } else {
            Session::flash('error', 'Error deleting role');
        }

        header('Location: /roles');
        exit;
    }
}
