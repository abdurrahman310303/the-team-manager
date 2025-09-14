<?php
/**
 * User Controller
 */

require_once __DIR__ . '/../models/User.php';

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {
        Auth::requireAdmin();
        
        $users = $this->userModel->getAllWithRoles();
        
        $currentPage = 'users';
        require_once __DIR__ . '/../views/users/index.php';
    }

    public function show()
    {
        Auth::requireAuth();
        
        $path = $_SERVER['REQUEST_URI'];
        $id = (int) basename($path);
        
        if (!$id) {
            header('Location: /users');
            exit;
        }
        
        $user = Auth::user();
        
        // Users can view their own profile, admins can view any user
        if (!Auth::hasRole('admin') && $user['id'] != $id) {
            Session::flash('error', 'Access denied');
            header('Location: /dashboard');
            exit;
        }

        $targetUser = $this->userModel->findWithRole($id);
        if (!$targetUser) {
            Session::flash('error', 'User not found');
            header('Location: /users');
            exit;
        }
        
        $currentPage = 'users';
        require_once __DIR__ . '/../views/users/show.php';
    }

    public function create()
    {
        Auth::requireAdmin();
        
        $roles = $this->userModel->getAllRoles();
        
        $currentPage = 'users';
        require_once __DIR__ . '/../views/users/create.php';
    }

    public function store()
    {
        Auth::requireAdmin();
        
        $required = ['name', 'email', 'password', 'role_id'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                Session::flash('error', 'Please fill in all required fields');
                header('Location: /users/create');
                exit;
            }
        }

        // Check if email already exists
        $existingUser = $this->userModel->findByEmail($_POST['email']);
        if ($existingUser) {
            Session::flash('error', 'Email already exists');
            header('Location: /users/create');
            exit;
        }

        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => $_POST['password'], // Model will hash it
            'role_id' => $_POST['role_id']
        ];

        $userId = $this->userModel->createUser($data);
        
        if ($userId) {
            Session::flash('success', 'User created successfully');
            header('Location: /users/' . $userId);
        } else {
            Session::flash('error', 'Error creating user');
            header('Location: /users/create');
        }
        exit;
    }

    public function edit()
    {
        Auth::requireAuth();
        
        $path = $_SERVER['REQUEST_URI'];
        $id = (int) basename(dirname($path));
        
        if (!$id) {
            header('Location: /users');
            exit;
        }
        
        $user = Auth::user();
        
        // Users can edit their own profile, admins can edit any user
        if (!Auth::hasRole('admin') && $user['id'] != $id) {
            Session::flash('error', 'Access denied');
            header('Location: /dashboard');
            exit;
        }

        $targetUser = $this->userModel->findWithRole($id);
        if (!$targetUser) {
            Session::flash('error', 'User not found');
            header('Location: /users');
            exit;
        }

        $roles = $this->userModel->getAllRoles();
        
        $currentPage = 'users';
        require_once __DIR__ . '/../views/users/edit.php';
    }

    public function update()
    {
        Auth::requireAuth();
        
        $path = $_SERVER['REQUEST_URI'];
        $segments = explode('/', trim($path, '/'));
        $id = null;
        
        foreach ($segments as $i => $segment) {
            if ($segment === 'users' && isset($segments[$i + 1]) && is_numeric($segments[$i + 1])) {
                $id = (int) $segments[$i + 1];
                break;
            }
        }
        
        if (!$id) {
            header('Location: /users');
            exit;
        }
        
        $user = Auth::user();
        
        // Users can edit their own profile, admins can edit any user
        if (!Auth::hasRole('admin') && $user['id'] != $id) {
            Session::flash('error', 'Access denied');
            header('Location: /dashboard');
            exit;
        }

        $required = ['name', 'email'];
        if (Auth::hasRole('admin')) {
            $required[] = 'role_id';
        }
        
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                Session::flash('error', 'Please fill in all required fields');
                header('Location: /users/' . $id . '/edit');
                exit;
            }
        }

        // Check if email already exists for other users
        $existingUser = $this->userModel->findByEmail($_POST['email']);
        if ($existingUser && $existingUser['id'] != $id) {
            Session::flash('error', 'Email already exists');
            header('Location: /users/' . $id . '/edit');
            exit;
        }

        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
        ];
        
        // Only admin can change roles
        if (Auth::hasRole('admin')) {
            $data['role_id'] = $_POST['role_id'];
        }

        // Only update password if provided
        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password']; // Model will hash it
        }

        if ($this->userModel->update($id, $data)) {
            Session::flash('success', 'User updated successfully');
            header('Location: /users/' . $id);
        } else {
            Session::flash('error', 'Error updating user');
            header('Location: /users/' . $id . '/edit');
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
            if ($segment === 'users' && isset($segments[$i + 1]) && is_numeric($segments[$i + 1])) {
                $id = (int) $segments[$i + 1];
                break;
            }
        }
        
        if (!$id) {
            header('Location: /users');
            exit;
        }
        
        $user = Auth::user();
        
        // Don't allow deleting self
        if ($user['id'] == $id) {
            Session::flash('error', 'Cannot delete your own account');
            header('Location: /users');
            exit;
        }

        if ($this->userModel->delete($id)) {
            Session::flash('success', 'User deleted successfully');
        } else {
            Session::flash('error', 'Error deleting user');
        }

        header('Location: /users');
        exit;
    }
}
