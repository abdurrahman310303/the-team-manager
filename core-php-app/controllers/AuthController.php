<?php
/**
 * Authentication Controller
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/Session.php';

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function showLogin() {
        if (Session::isLoggedIn()) {
            header('Location: /dashboard');
            exit;
        }
        
        require_once __DIR__ . '/../views/auth/login.php';
    }
    
    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            Session::flash('error', 'Email and password are required');
            header('Location: /login');
            exit;
        }
        
        $user = $this->userModel->findByEmail($email);
        
        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            Session::flash('error', 'Invalid credentials');
            header('Location: /login');
            exit;
        }
        
        Session::setUser($user);
        Session::flash('success', 'Welcome back, ' . $user['name']);
        
        header('Location: /dashboard');
        exit;
    }
    
    public function showRegister() {
        if (Session::isLoggedIn()) {
            header('Location: /dashboard');
            exit;
        }
        
        require_once __DIR__ . '/../views/auth/register.php';
    }
    
    public function register() {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['password_confirmation'] ?? '';
        
        // Validation
        if (empty($name) || empty($email) || empty($password)) {
            Session::flash('error', 'All fields are required');
            header('Location: /register');
            exit;
        }
        
        if ($password !== $confirmPassword) {
            Session::flash('error', 'Passwords do not match');
            header('Location: /register');
            exit;
        }
        
        // Check if user exists
        $existingUser = $this->userModel->findByEmail($email);
        if ($existingUser) {
            Session::flash('error', 'Email already exists');
            header('Location: /register');
            exit;
        }
        
        // Create user (default role_id = 2 for developer)
        $userId = $this->userModel->createUser([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role_id' => 2 // Default to developer role
        ]);
        
        Session::flash('success', 'Registration successful! Please log in.');
        header('Location: /login');
        exit;
    }
    
    public function logout() {
        Session::logout();
        Session::destroy();
        header('Location: /login');
        exit;
    }
}
