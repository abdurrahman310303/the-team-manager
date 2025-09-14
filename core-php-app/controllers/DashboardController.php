<?php
/**
 * Dashboard Controller
 */

class DashboardController {
    private $userModel;
    private $projectModel;
    private $leadModel;
    private $dailyReportModel;
    private $expenseModel;
    private $paymentModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->projectModel = new Project();
        $this->leadModel = new Lead();
        $this->dailyReportModel = new DailyReport();
        $this->expenseModel = new Expense();
        $this->paymentModel = new Payment();
    }
    
    public function index() {
        $this->requireAuth();
        
        $user = Session::getUser();
        $stats = $this->getDashboardStats($user);
        $dashboardData = $this->getRoleSpecificData($user);
        
        require_once __DIR__ . '/../views/dashboard/index.php';
    }
    
    private function getDashboardStats($user) {
        $db = Database::getInstance();
        $stats = [];
        
        switch ($user['role_name']) {
            case 'admin':
                $stats = [
                    'total_projects' => $db->fetch("SELECT COUNT(*) as count FROM projects")['count'],
                    'active_projects' => $db->fetch("SELECT COUNT(*) as count FROM projects WHERE status IN ('planning', 'in_progress')")['count'],
                    'total_leads' => $db->fetch("SELECT COUNT(*) as count FROM leads")['count'],
                    'pending_expenses' => $db->fetch("SELECT COUNT(*) as count FROM expenses WHERE status = 'pending'")['count'] ?? 0,
                    'total_users' => $db->fetch("SELECT COUNT(*) as count FROM users")['count'],
                    'total_payments' => $db->fetch("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'completed'")['total'],
                ];
                break;
                
            case 'developer':
                $userId = $user['id'];
                $stats = [
                    'total_projects' => $db->fetch("SELECT COUNT(*) as count FROM projects")['count'],
                    'my_reports_this_month' => $db->fetch("SELECT COUNT(*) as count FROM daily_reports WHERE user_id = ? AND DATE_FORMAT(report_date, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')", [$userId])['count'],
                    'total_hours_this_month' => $db->fetch("SELECT COALESCE(SUM(hours_worked), 0) as total FROM daily_reports WHERE user_id = ? AND DATE_FORMAT(report_date, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')", [$userId])['total'],
                    'pending_expenses' => $db->fetch("SELECT COUNT(*) as count FROM expenses WHERE added_by = ? AND status = 'pending'", [$userId])['count'],
                ];
                break;
                
            case 'bd':
                $userId = $user['id'];
                $stats = [
                    'total_leads' => $db->fetch("SELECT COUNT(*) as count FROM leads")['count'],
                    'my_leads' => $db->fetch("SELECT COUNT(*) as count FROM leads WHERE assigned_to = ?", [$userId])['count'],
                    'qualified_leads' => $db->fetch("SELECT COUNT(*) as count FROM leads WHERE assigned_to = ? AND status = 'qualified'", [$userId])['count'],
                    'won_leads' => $db->fetch("SELECT COUNT(*) as count FROM leads WHERE assigned_to = ? AND status = 'won'", [$userId])['count'],
                ];
                break;
                
            case 'investor':
                $stats = [
                    'total_projects' => $db->fetch("SELECT COUNT(*) as count FROM projects")['count'],
                    'total_investment' => $db->fetch("SELECT COALESCE(SUM(budget), 0) as total FROM projects")['total'],
                    'completed_payments' => $db->fetch("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'completed'")['total'],
                    'pending_payments' => $db->fetch("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'pending'")['total'],
                ];
                break;
                
            default:
                $userId = $user['id'];
                $stats = [
                    'my_reports_this_month' => $db->fetch("SELECT COUNT(*) as count FROM daily_reports WHERE user_id = ? AND DATE_FORMAT(report_date, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')", [$userId])['count'],
                    'total_hours_this_month' => $db->fetch("SELECT COALESCE(SUM(hours_worked), 0) as total FROM daily_reports WHERE user_id = ? AND DATE_FORMAT(report_date, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')", [$userId])['total'],
                    'pending_expenses' => $db->fetch("SELECT COUNT(*) as count FROM expenses WHERE added_by = ? AND status = 'pending'", [$userId])['count'],
                ];
        }
        
        return $stats;
    }
    
    private function getRoleSpecificData($user) {
        $data = [];
        
        switch ($user['role_name']) {
            case 'admin':
                $data['recent_projects'] = array_slice($this->projectModel->getAllWithRelations(), 0, 5);
                $data['recent_reports'] = array_slice($this->dailyReportModel->getAll(), 0, 5);
                $data['pending_expenses'] = array_slice($this->expenseModel->getByStatus('pending'), 0, 5);
                break;
                
            case 'developer':
                $data['recent_projects'] = array_slice($this->projectModel->getAllWithRelations(), 0, 3);
                $data['my_recent_reports'] = array_slice($this->dailyReportModel->getByUserId($user['id']), 0, 5);
                break;
                
            case 'bd':
                $data['my_recent_leads'] = array_slice($this->leadModel->getByAssignedUser($user['id']), 0, 5);
                $data['my_recent_reports'] = array_slice($this->dailyReportModel->getByUserId($user['id']), 0, 5);
                break;
                
            case 'investor':
                $data['recent_projects'] = array_slice($this->projectModel->getAllWithRelations(), 0, 5);
                $data['recent_payments'] = array_slice($this->paymentModel->getAll(), 0, 5);
                break;
                
            default:
                $data['my_recent_reports'] = array_slice($this->dailyReportModel->getByUserId($user['id']), 0, 5);
        }
        
        return $data;
    }
    
    private function requireAuth() {
        if (!Session::isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }
}
