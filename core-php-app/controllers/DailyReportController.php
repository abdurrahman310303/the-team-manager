<?php
/**
 * Daily Report Controller
 */

require_once __DIR__ . '/../models/DailyReport.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Project.php';

class DailyReportController
{
    private $dailyReportModel;
    private $userModel;
    private $projectModel;

    public function __construct()
    {
        $this->dailyReportModel = new DailyReport();
        $this->userModel = new User();
        $this->projectModel = new Project();
    }

    public function index()
    {
        Auth::requireNonInvestor(); // Everyone except investors
        
        $user = Auth::user();
        
        // Get reports based on role
        if (Auth::hasRole('admin')) {
            // Admin sees all reports
            $reports = $this->dailyReportModel->getAllWithUser();
        } else {
            // Others see only their own reports
            $reports = $this->dailyReportModel->getByUserId($user['id']);
        }
        
        $currentPage = 'daily-reports';
        require_once __DIR__ . '/../views/daily-reports/index.php';
    }

    public function show()
    {
        Auth::requireNonInvestor();
        
        $path = $_SERVER['REQUEST_URI'];
        $id = (int) basename($path);
        
        if (!$id) {
            header('Location: /daily-reports');
            exit;
        }
        
        $report = $this->dailyReportModel->findWithUser($id);
        if (!$report) {
            Session::flash('error', 'Daily report not found');
            header('Location: /daily-reports');
            exit;
        }

        // Check view permissions
        if (!Auth::canViewReport($report)) {
            Session::flash('error', 'Access denied to this report');
            header('Location: /daily-reports');
            exit;
        }
        
        $currentPage = 'daily-reports';
        require_once __DIR__ . '/../views/daily-reports/show.php';
    }

    public function create()
    {
        Auth::requireNonInvestor();
        
        $user = Auth::user();
        $projects = $this->projectModel->getAll();
        
        $currentPage = 'daily-reports';
        require_once __DIR__ . '/../views/daily-reports/create.php';
    }

    public function store()
    {
        Auth::requireNonInvestor();
        
        $required = ['report_type', 'report_date', 'work_completed'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                Session::flash('error', 'Please fill in all required fields');
                header('Location: /daily-reports/create');
                exit;
            }
        }

        $user = Auth::user();
        
        // Validate report type based on user role
        $reportType = $_POST['report_type'];
        if ($reportType === 'bd' && !Auth::hasAnyRole(['bd', 'admin'])) {
            Session::flash('error', 'Invalid report type for your role');
            header('Location: /daily-reports/create');
            exit;
        }
        
        if ($reportType === 'developer' && !Auth::hasAnyRole(['developer', 'admin'])) {
            Session::flash('error', 'Invalid report type for your role');
            header('Location: /daily-reports/create');
            exit;
        }

        $data = [
            'user_id' => $user['id'],
            'project_id' => !empty($_POST['project_id']) ? $_POST['project_id'] : null,
            'report_type' => $reportType,
            'report_date' => $_POST['report_date'],
            'work_completed' => $_POST['work_completed'],
            'challenges_faced' => $_POST['challenges_faced'] ?? '',
            'next_plans' => $_POST['next_plans'] ?? '',
            'hours_worked' => $_POST['hours_worked'] ?? 0,
            'leads_generated' => $_POST['leads_generated'] ?? 0,
            'proposals_submitted' => $_POST['proposals_submitted'] ?? 0,
            'projects_locked' => $_POST['projects_locked'] ?? 0,
            'revenue_generated' => $_POST['revenue_generated'] ?? 0,
            'notes' => $_POST['notes'] ?? '',
        ];

        $reportId = $this->dailyReportModel->create($data);
        
        if ($reportId) {
            Session::flash('success', 'Daily report created successfully');
            header('Location: /daily-reports');
        } else {
            Session::flash('error', 'Error creating daily report');
            header('Location: /daily-reports/create');
        }
        exit;
    }

    public function edit()
    {
        Auth::requireNonInvestor();
        
        $path = $_SERVER['REQUEST_URI'];
        $id = (int) basename(dirname($path));
        
        if (!$id) {
            header('Location: /daily-reports');
            exit;
        }
        
        $report = $this->dailyReportModel->findWithUser($id);
        if (!$report) {
            Session::flash('error', 'Daily report not found');
            header('Location: /daily-reports');
            exit;
        }

        // Check edit permissions
        if (!Auth::canEditReport($report)) {
            Session::flash('error', 'You can only edit your own reports within 24 hours of creation');
            header('Location: /daily-reports/' . $id);
            exit;
        }
        
        $projects = $this->projectModel->getAll();
        
        $currentPage = 'daily-reports';
        require_once __DIR__ . '/../views/daily-reports/edit.php';
    }

    public function update()
    {
        Auth::requireNonInvestor();
        
        $path = $_SERVER['REQUEST_URI'];
        $segments = explode('/', trim($path, '/'));
        $id = null;
        
        foreach ($segments as $i => $segment) {
            if ($segment === 'daily-reports' && isset($segments[$i + 1]) && is_numeric($segments[$i + 1])) {
                $id = (int) $segments[$i + 1];
                break;
            }
        }
        
        if (!$id) {
            header('Location: /daily-reports');
            exit;
        }
        
        $report = $this->dailyReportModel->find($id);
        if (!$report) {
            Session::flash('error', 'Daily report not found');
            header('Location: /daily-reports');
            exit;
        }

        // Check edit permissions
        if (!Auth::canEditReport($report)) {
            Session::flash('error', 'You can only edit your own reports within 24 hours of creation');
            header('Location: /daily-reports/' . $id);
            exit;
        }

        $required = ['report_type', 'report_date', 'work_completed'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                Session::flash('error', 'Please fill in all required fields');
                header('Location: /daily-reports/' . $id . '/edit');
                exit;
            }
        }

        $data = [
            'project_id' => !empty($_POST['project_id']) ? $_POST['project_id'] : null,
            'report_type' => $_POST['report_type'],
            'report_date' => $_POST['report_date'],
            'work_completed' => $_POST['work_completed'],
            'challenges_faced' => $_POST['challenges_faced'] ?? '',
            'next_plans' => $_POST['next_plans'] ?? '',
            'hours_worked' => $_POST['hours_worked'] ?? 0,
            'leads_generated' => $_POST['leads_generated'] ?? 0,
            'proposals_submitted' => $_POST['proposals_submitted'] ?? 0,
            'projects_locked' => $_POST['projects_locked'] ?? 0,
            'revenue_generated' => $_POST['revenue_generated'] ?? 0,
            'notes' => $_POST['notes'] ?? '',
        ];

        if ($this->dailyReportModel->update($id, $data)) {
            Session::flash('success', 'Daily report updated successfully');
            header('Location: /daily-reports');
        } else {
            Session::flash('error', 'Error updating daily report');
            header('Location: /daily-reports/' . $id . '/edit');
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
            if ($segment === 'daily-reports' && isset($segments[$i + 1]) && is_numeric($segments[$i + 1])) {
                $id = (int) $segments[$i + 1];
                break;
            }
        }
        
        if (!$id) {
            header('Location: /daily-reports');
            exit;
        }

        if ($this->dailyReportModel->delete($id)) {
            Session::flash('success', 'Daily report deleted successfully');
        } else {
            Session::flash('error', 'Error deleting daily report');
        }

        header('Location: /daily-reports');
        exit;
    }
}
