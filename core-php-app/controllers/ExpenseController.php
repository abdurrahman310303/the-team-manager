<?php
/**
 * Expense Controller - Manages expense submission and approval workflow
 * Access: Non-investors can submit expenses, admin can approve/reject
 */

require_once __DIR__ . '/../models/Expense.php';
require_once __DIR__ . '/../models/User.php';

class ExpenseController
{
    private $expenseModel;
    private $userModel;

    public function __construct()
    {
        $this->expenseModel = new Expense();
        $this->userModel = new User();
    }

    public function index()
    {
        Auth::requireNonInvestor();
        
        $user = Auth::user();

        // Admins see all expenses, employees see only their own
        if (Auth::hasRole('admin')) {
            $expenses = $this->expenseModel->getAllWithUsers();
        } else {
            $expenses = $this->expenseModel->getByUserId($user['id']);
        }

        $currentPage = 'expenses';
        require_once __DIR__ . '/../views/expenses/index.php';
    }

    public function show()
    {
        Auth::requireNonInvestor();
        
        $path = $_SERVER['REQUEST_URI'];
        $id = (int) basename($path);
        
        if (!$id) {
            header('Location: /expenses');
            exit;
        }

        $expense = $this->expenseModel->findWithUser($id);
        if (!$expense) {
            Session::flash('error', 'Expense not found');
            header('Location: /expenses');
            exit;
        }

        $user = Auth::user();
        
        // Check permissions - users can only view their own expenses unless they're admin
        if (!Auth::hasRole('admin') && $expense['added_by'] != $user['id']) {
            Session::flash('error', 'Access denied');
            header('Location: /expenses');
            exit;
        }

        $currentPage = 'expenses';
        require_once __DIR__ . '/../views/expenses/show.php';
    }

    public function create()
    {
        Auth::requireNonInvestor();
        
        $categories = [
            'office_supplies' => 'Office Supplies',
            'travel' => 'Travel',
            'meals' => 'Meals & Entertainment',
            'software' => 'Software & Tools',
            'equipment' => 'Equipment',
            'marketing' => 'Marketing',
            'other' => 'Other'
        ];

        $currentPage = 'expenses';
        require_once __DIR__ . '/../views/expenses/create.php';
    }

    public function store()
    {
        Auth::requireNonInvestor();
        
        $user = Auth::user();

        $required = ['title', 'description', 'amount', 'category'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                Session::flash('error', 'Please fill in all required fields');
                header('Location: /expenses/create');
                exit;
            }
        }

        // Validate amount
        if (!is_numeric($_POST['amount']) || $_POST['amount'] <= 0) {
            Session::flash('error', 'Please enter a valid amount');
            header('Location: /expenses/create');
            exit;
        }

        $data = [
            'project_id' => 2, // Default project for now - should be made configurable
            'added_by' => $user['id'],
            'title' => trim($_POST['title']),
            'description' => trim($_POST['description']),
            'amount' => (float) $_POST['amount'],
            'category' => $_POST['category'],
            'expense_date' => !empty($_POST['expense_date']) ? $_POST['expense_date'] : date('Y-m-d'),
            'status' => 'pending' // Default status for new expenses
        ];

        // Handle file upload
        if (isset($_FILES['receipt_image']) && $_FILES['receipt_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/receipts/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileExtension = pathinfo($_FILES['receipt_image']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['receipt_image']['tmp_name'], $uploadPath)) {
                $data['receipt_image'] = 'uploads/receipts/' . $fileName; // Use receipt_image to match database
            }
        }

        $expenseId = $this->expenseModel->create($data);
        
        if ($expenseId) {
            Session::flash('success', 'Expense submitted successfully');
            header('Location: /expenses');
        } else {
            Session::flash('error', 'Error submitting expense');
            header('Location: /expenses/create');
        }
        exit;
    }

    public function edit()
    {
        Auth::requireNonInvestor();
        
        $path = $_SERVER['REQUEST_URI'];
        $id = (int) basename(dirname($path));
        
        if (!$id) {
            header('Location: /expenses');
            exit;
        }

        $expense = $this->expenseModel->find($id);
        if (!$expense) {
            Session::flash('error', 'Expense not found');
            header('Location: /expenses');
            exit;
        }

        $user = Auth::user();
        
        // Check permissions - users can only edit their own pending expenses unless they're admin
        if (!Auth::hasRole('admin')) {
            if ($expense['added_by'] != $user['id'] || $expense['status'] !== 'pending') {
                Session::flash('error', 'Access denied or expense cannot be edited');
                header('Location: /expenses');
                exit;
            }
        }

        $categories = [
            'office_supplies' => 'Office Supplies',
            'travel' => 'Travel',
            'meals' => 'Meals & Entertainment',
            'software' => 'Software & Tools',
            'equipment' => 'Equipment',
            'marketing' => 'Marketing',
            'other' => 'Other'
        ];

        $currentPage = 'expenses';
        require_once __DIR__ . '/../views/expenses/edit.php';
    }

    public function update()
    {
        Auth::requireNonInvestor();
        
        $path = $_SERVER['REQUEST_URI'];
        $segments = explode('/', trim($path, '/'));
        $id = null;
        
        foreach ($segments as $i => $segment) {
            if ($segment === 'expenses' && isset($segments[$i + 1]) && is_numeric($segments[$i + 1])) {
                $id = (int) $segments[$i + 1];
                break;
            }
        }
        
        if (!$id) {
            header('Location: /expenses');
            exit;
        }

        $expense = $this->expenseModel->find($id);
        if (!$expense) {
            Session::flash('error', 'Expense not found');
            header('Location: /expenses');
            exit;
        }

        $user = Auth::user();
        
        // Check permissions
        if (!Auth::hasRole('admin')) {
            if ($expense['added_by'] != $user['id'] || $expense['status'] !== 'pending') {
                Session::flash('error', 'Access denied or expense cannot be edited');
                header('Location: /expenses');
                exit;
            }
        }

        $required = ['title', 'description', 'amount', 'category'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                Session::flash('error', 'Please fill in all required fields');
                header('Location: /expenses/' . $id . '/edit');
                exit;
            }
        }

        // Validate amount
        if (!is_numeric($_POST['amount']) || $_POST['amount'] <= 0) {
            Session::flash('error', 'Please enter a valid amount');
            header('Location: /expenses/' . $id . '/edit');
            exit;
        }

        $data = [
            'title' => trim($_POST['title']),
            'description' => trim($_POST['description']),
            'amount' => (float) $_POST['amount'],
            'category' => $_POST['category'],
            'expense_date' => !empty($_POST['expense_date']) ? $_POST['expense_date'] : $expense['expense_date']
        ];

        // Handle file upload
        if (isset($_FILES['receipt_image']) && $_FILES['receipt_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/receipts/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileExtension = pathinfo($_FILES['receipt_image']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['receipt_image']['tmp_name'], $uploadPath)) {
                // Delete old file if it exists
                if (!empty($expense['receipt_image'])) {
                    $oldFile = __DIR__ . '/../' . $expense['receipt_image'];
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
                $data['receipt_image'] = 'uploads/receipts/' . $fileName;
            }
        }

        // Admins can also update status
        if (Auth::hasRole('admin') && isset($_POST['status'])) {
            $data['status'] = $_POST['status'];
        }

        if ($this->expenseModel->update($id, $data)) {
            Session::flash('success', 'Expense updated successfully');
            header('Location: /expenses');
        } else {
            Session::flash('error', 'Error updating expense');
            header('Location: /expenses/' . $id . '/edit');
        }
        exit;
    }

    public function approve()
    {
        Auth::requireAdmin();
        
        $path = $_SERVER['REQUEST_URI'];
        $segments = explode('/', trim($path, '/'));
        $id = null;
        
        foreach ($segments as $i => $segment) {
            if ($segment === 'expenses' && isset($segments[$i + 1]) && is_numeric($segments[$i + 1])) {
                $id = (int) $segments[$i + 1];
                break;
            }
        }
        
        if (!$id) {
            header('Location: /expenses');
            exit;
        }

        if ($this->expenseModel->updateStatus($id, 'approved')) {
            Session::flash('success', 'Expense approved successfully');
        } else {
            Session::flash('error', 'Error approving expense');
        }

        header('Location: /expenses/' . $id);
        exit;
    }

    public function reject()
    {
        Auth::requireAdmin();
        
        $path = $_SERVER['REQUEST_URI'];
        $segments = explode('/', trim($path, '/'));
        $id = null;
        
        foreach ($segments as $i => $segment) {
            if ($segment === 'expenses' && isset($segments[$i + 1]) && is_numeric($segments[$i + 1])) {
                $id = (int) $segments[$i + 1];
                break;
            }
        }
        
        if (!$id) {
            header('Location: /expenses');
            exit;
        }

        if ($this->expenseModel->updateStatus($id, 'rejected')) {
            Session::flash('success', 'Expense rejected');
        } else {
            Session::flash('error', 'Error rejecting expense');
        }

        header('Location: /expenses/' . $id);
        exit;
    }

    public function delete()
    {
        Auth::requireNonInvestor();
        
        $path = $_SERVER['REQUEST_URI'];
        $segments = explode('/', trim($path, '/'));
        $id = null;
        
        foreach ($segments as $i => $segment) {
            if ($segment === 'expenses' && isset($segments[$i + 1]) && is_numeric($segments[$i + 1])) {
                $id = (int) $segments[$i + 1];
                break;
            }
        }
        
        if (!$id) {
            header('Location: /expenses');
            exit;
        }

        $expense = $this->expenseModel->find($id);
        if (!$expense) {
            Session::flash('error', 'Expense not found');
            header('Location: /expenses');
            exit;
        }

        $user = Auth::user();
        
        // Check permissions
        if (!Auth::hasRole('admin') && $expense['added_by'] != $user['id']) {
            Session::flash('error', 'Access denied');
            header('Location: /expenses');
            exit;
        }

        if ($this->expenseModel->delete($id)) {
            Session::flash('success', 'Expense deleted successfully');
        } else {
            Session::flash('error', 'Error deleting expense');
        }

        header('Location: /expenses');
        exit;
    }
}
