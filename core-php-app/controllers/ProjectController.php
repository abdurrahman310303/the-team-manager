<?php
/**
 * Project Controller
 */

require_once __DIR__ . '/../models/Project.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/Auth.php';

class ProjectController {
    private $projectModel;
    private $userModel;
    
    public function __construct() {
        $this->projectModel = new Project();
        $this->userModel = new User();
    }
    
    public function index() {
        Auth::requireAuth();
        
        $user = Auth::user();
        
        // Admins and investors see all projects, others see only their assigned projects
        if (Auth::hasAnyRole(['admin', 'investor'])) {
            $projects = $this->projectModel->getAllWithRelations();
        } else {
            $projects = $this->projectModel->getUserProjects($user['id']);
        }
        
        $currentPage = 'projects';
        require_once __DIR__ . '/../views/projects/index.php';
    }
    
    public function show() {
        Auth::requireAuth();
        
        $path = $_SERVER['REQUEST_URI'];
        $id = (int) basename($path);
        
        if (!$id) {
            header('Location: /projects');
            exit;
        }
        
        $project = $this->projectModel->findWithRelations($id);
        
        if (!$project) {
            Session::flash('error', 'Project not found');
            header('Location: /projects');
            exit;
        }
        
        // Check if user has access to this project
        $user = Auth::user();
        if (!Auth::hasAnyRole(['admin', 'investor'])) {
            $userProjects = $this->projectModel->getUserProjects($user['id']);
            $hasAccess = false;
            foreach ($userProjects as $userProject) {
                if ($userProject['id'] == $id) {
                    $hasAccess = true;
                    break;
                }
            }
            if (!$hasAccess) {
                Session::flash('error', 'Access denied to this project');
                header('Location: /projects');
                exit;
            }
        }
        
        // Get assigned users for this project
        $assignedUsers = $this->projectModel->getProjectUsers($id);
        
        $currentPage = 'projects';
        require_once __DIR__ . '/../views/projects/show.php';
    }
    
    public function create() {
        Auth::requireAdmin();
        
        $users = $this->userModel->getAll();
        
        $currentPage = 'projects';
        require_once __DIR__ . '/../views/projects/create.php';
    }
    
    public function store() {
        Auth::requireAdmin();
        
        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'status' => $_POST['status'] ?? 'planning',
            'budget' => !empty($_POST['budget']) ? (float)$_POST['budget'] : null,
            'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
            'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
            'project_manager_id' => !empty($_POST['project_manager_id']) ? (int)$_POST['project_manager_id'] : null,
            'client_id' => !empty($_POST['client_id']) ? (int)$_POST['client_id'] : null,
        ];
        
        // Basic validation
        if (empty($data['name']) || empty($data['project_manager_id'])) {
            Session::flash('error', 'Name and Project Manager are required');
            header('Location: /projects/create');
            exit;
        }
        
        try {
            $projectId = $this->projectModel->create($data);
            
            // Convert to integer and check if we have a reasonable ID
            $projectId = (int) $projectId;
            
            // Only fail if we get 0 or negative ID
            if ($projectId <= 0) {
                throw new Exception("Failed to create project - no valid ID generated");
            }
            
            Session::flash('success', 'Project created successfully! You can assign team members by editing the project.');
            
        } catch (Exception $e) {
            Session::flash('error', 'Failed to create project: ' . $e->getMessage());
            header('Location: /projects/create');
            exit;
        }
        
        header('Location: /projects');
        exit;
    }
    
    public function edit() {
        Auth::requireAdmin();
        
        $path = $_SERVER['REQUEST_URI'];
        $id = (int) basename(dirname($path));
        
        if (!$id) {
            header('Location: /projects');
            exit;
        }
        
        $project = $this->projectModel->findWithRelations($id);
        
        if (!$project) {
            Session::flash('error', 'Project not found');
            header('Location: /projects');
            exit;
        }
        
        $users = $this->userModel->getAll();
        $assignedUsers = $this->projectModel->getProjectUsers($id);
        
        $currentPage = 'projects';
        require_once __DIR__ . '/../views/projects/edit.php';
    }
    
    public function update() {
        Auth::requireAdmin();
        
        $path = $_SERVER['REQUEST_URI'];
        $segments = explode('/', trim($path, '/'));
        $id = null;
        
        foreach ($segments as $i => $segment) {
            if ($segment === 'projects' && isset($segments[$i + 1]) && is_numeric($segments[$i + 1])) {
                $id = (int) $segments[$i + 1];
                break;
            }
        }
        
        if (!$id) {
            header('Location: /projects');
            exit;
        }
        
        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'status' => $_POST['status'] ?? 'planning',
            'budget' => $_POST['budget'] ?? null,
            'start_date' => $_POST['start_date'] ?? null,
            'end_date' => $_POST['end_date'] ?? null,
            'project_manager_id' => $_POST['project_manager_id'] ?? null,
            'client_id' => $_POST['client_id'] ?? null,
        ];
        
        // Basic validation
        if (empty($data['name']) || empty($data['project_manager_id'])) {
            Session::flash('error', 'Name and Project Manager are required');
            header('Location: /projects/' . $id . '/edit');
            exit;
        }
        
        $this->projectModel->update($id, $data);
        
        // Debug: Log what users are being assigned in update
        error_log("UPDATE - POST data for assigned_users: " . var_export($_POST['assigned_users'] ?? 'NOT_SET', true));
        
        // Update assigned users
        if (isset($_POST['assigned_users']) && is_array($_POST['assigned_users'])) {
            error_log("UPDATE - Assigning users: " . implode(', ', $_POST['assigned_users']) . " to project " . $id);
            $this->projectModel->assignUsers($id, $_POST['assigned_users']);
        } else {
            error_log("UPDATE - Clearing all user assignments for project " . $id);
            $this->projectModel->assignUsers($id, []);
        }
        
        Session::flash('success', 'Project updated successfully');
        header('Location: /projects/' . $id);
        exit;
    }
    
    public function delete() {
        Auth::requireAdmin();
        
        $path = $_SERVER['REQUEST_URI'];
        $segments = explode('/', trim($path, '/'));
        $id = null;
        
        foreach ($segments as $i => $segment) {
            if ($segment === 'projects' && isset($segments[$i + 1]) && is_numeric($segments[$i + 1])) {
                $id = (int) $segments[$i + 1];
                break;
            }
        }
        
        if (!$id) {
            header('Location: /projects');
            exit;
        }
        
        $this->projectModel->delete($id);
        
        Session::flash('success', 'Project deleted successfully');
        header('Location: /projects');
        exit;
    }
}
