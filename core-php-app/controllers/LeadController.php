<?php
/**
 * Lead Controller
 */

require_once __DIR__ . '/../models/Lead.php';
require_once __DIR__ . '/../models/User.php';

class LeadController
{
    private $leadModel;
    private $userModel;

    public function __construct()
    {
        $this->leadModel = new Lead();
        $this->userModel = new User();
    }

    public function index()
    {
        Auth::requireAuth();
        
        $user = Auth::user();
        
        // Get leads based on role
        if (Auth::hasRole('admin')) {
            // Admin sees all leads
            $leads = $this->leadModel->getAllWithAssignedUser();
        } elseif (Auth::hasRole('bd')) {
            // BD sees their own leads
            $leads = $this->leadModel->getByAssignedUser($user['id']);
        } elseif (Auth::hasRole('investor')) {
            // Investors can see all leads (read-only)
            $leads = $this->leadModel->getAllWithAssignedUser();
        } else {
            // Other roles can't access leads
            Session::flash('error', 'Access denied to leads section');
            header('Location: /dashboard');
            exit;
        }
        
        $currentPage = 'leads';
        require_once __DIR__ . '/../views/leads/index.php';
    }

    public function show()
    {
        Auth::requireAuth();
        
        $path = $_SERVER['REQUEST_URI'];
        $id = (int) basename($path);
        
        if (!$id) {
            header('Location: /leads');
            exit;
        }
        
        $lead = $this->leadModel->findWithAssignedUser($id);
        if (!$lead) {
            Session::flash('error', 'Lead not found');
            header('Location: /leads');
            exit;
        }
        
        // Check access permissions
        if (!Auth::canViewLead($lead)) {
            Session::flash('error', 'Access denied to this lead');
            header('Location: /leads');
            exit;
        }
        
        $currentPage = 'leads';
        require_once __DIR__ . '/../views/leads/show.php';
    }

    public function create()
    {
        Auth::requireBDOrAdmin();
        
        $bdUsers = $this->userModel->getUsersByRole('bd');
        
        $currentPage = 'leads';
        require_once __DIR__ . '/../views/leads/create.php';
    }

    public function store()
    {
        Auth::requireBDOrAdmin();
        
        $required = ['company_name', 'contact_person', 'email'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                Session::flash('error', 'Please fill in all required fields');
                header('Location: /leads/create');
                exit;
            }
        }

        $user = Auth::user();
        
        $data = [
            'company_name' => $_POST['company_name'],
            'contact_person' => $_POST['contact_person'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'] ?? '',
            'description' => $_POST['description'] ?? '',
            'source' => $_POST['source'] ?? 'website',
            'status' => $_POST['status'] ?? 'new',
            'estimated_value' => !empty($_POST['estimated_value']) ? $_POST['estimated_value'] : null,
            'notes' => $_POST['notes'] ?? '',
            'assigned_to' => !empty($_POST['assigned_to']) ? $_POST['assigned_to'] : $user['id'],
            'last_contact_date' => !empty($_POST['last_contact_date']) ? $_POST['last_contact_date'] : null,
        ];

        $leadId = $this->leadModel->create($data);
        
        if ($leadId) {
            Session::flash('success', 'Lead created successfully');
            header('Location: /leads/' . $leadId);
        } else {
            Session::flash('error', 'Error creating lead');
            header('Location: /leads/create');
        }
        exit;
    }

    public function edit()
    {
        Auth::requireBDOrAdmin();
        
        $path = $_SERVER['REQUEST_URI'];
        $id = (int) basename(dirname($path));
        
        if (!$id) {
            header('Location: /leads');
            exit;
        }
        
        $lead = $this->leadModel->findWithAssignedUser($id);
        if (!$lead) {
            Session::flash('error', 'Lead not found');
            header('Location: /leads');
            exit;
        }
        
        // Check edit permissions
        if (!Auth::canEditLead($lead)) {
            Session::flash('error', 'You can only edit your own leads');
            header('Location: /leads/' . $id);
            exit;
        }
        
        $bdUsers = $this->userModel->getUsersByRole('bd');
        
        $currentPage = 'leads';
        require_once __DIR__ . '/../views/leads/edit.php';
    }

    public function update()
    {
        Auth::requireBDOrAdmin();
        
        $path = $_SERVER['REQUEST_URI'];
        $segments = explode('/', trim($path, '/'));
        $id = null;
        
        foreach ($segments as $i => $segment) {
            if ($segment === 'leads' && isset($segments[$i + 1]) && is_numeric($segments[$i + 1])) {
                $id = (int) $segments[$i + 1];
                break;
            }
        }
        
        if (!$id) {
            header('Location: /leads');
            exit;
        }
        
        $lead = $this->leadModel->find($id);
        if (!$lead) {
            Session::flash('error', 'Lead not found');
            header('Location: /leads');
            exit;
        }
        
        // Check edit permissions
        if (!Auth::canEditLead($lead)) {
            Session::flash('error', 'You can only edit your own leads');
            header('Location: /leads/' . $id);
            exit;
        }
        
        $required = ['company_name', 'contact_person', 'email'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                Session::flash('error', 'Please fill in all required fields');
                header('Location: /leads/' . $id . '/edit');
                exit;
            }
        }

        $data = [
            'company_name' => $_POST['company_name'],
            'contact_person' => $_POST['contact_person'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'] ?? '',
            'description' => $_POST['description'] ?? '',
            'source' => $_POST['source'] ?? 'website',
            'status' => $_POST['status'] ?? 'new',
            'estimated_value' => !empty($_POST['estimated_value']) ? $_POST['estimated_value'] : null,
            'notes' => $_POST['notes'] ?? '',
            'assigned_to' => !empty($_POST['assigned_to']) ? $_POST['assigned_to'] : $lead['assigned_to'],
            'last_contact_date' => !empty($_POST['last_contact_date']) ? $_POST['last_contact_date'] : null,
        ];

        if ($this->leadModel->update($id, $data)) {
            Session::flash('success', 'Lead updated successfully');
            header('Location: /leads/' . $id);
        } else {
            Session::flash('error', 'Error updating lead');
            header('Location: /leads/' . $id . '/edit');
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
            if ($segment === 'leads' && isset($segments[$i + 1]) && is_numeric($segments[$i + 1])) {
                $id = (int) $segments[$i + 1];
                break;
            }
        }
        
        if (!$id) {
            header('Location: /leads');
            exit;
        }

        if ($this->leadModel->delete($id)) {
            Session::flash('success', 'Lead deleted successfully');
        } else {
            Session::flash('error', 'Error deleting lead');
        }

        header('Location: /leads');
        exit;
    }
}
