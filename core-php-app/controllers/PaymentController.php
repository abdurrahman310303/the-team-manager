<?php
/**
 * Payment Controller
 */

require_once __DIR__ . '/../models/Payment.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Project.php';

class PaymentController
{
    private $paymentModel;
    private $userModel;
    private $projectModel;

    public function __construct()
    {
        $this->paymentModel = new Payment();
        $this->userModel = new User();
        $this->projectModel = new Project();
    }

    public function index()
    {
        Auth::requireInvestorOrAdmin();
        
        $user = Auth::user();
        
        // Get payments based on role
        if (Auth::hasRole('admin')) {
            // Admin sees all payments
            $payments = $this->paymentModel->getAllWithRelations();
        } elseif (Auth::hasRole('investor')) {
            // Investors see payments they've made or all payments for overview
            $payments = $this->paymentModel->getAllWithRelations();
        }
        
        $currentPage = 'payments';
        require_once __DIR__ . '/../views/payments/index.php';
    }

    public function show()
    {
        Auth::requireInvestorOrAdmin();
        
        $path = $_SERVER['REQUEST_URI'];
        $id = (int) basename($path);
        
        if (!$id) {
            header('Location: /payments');
            exit;
        }
        
        $payment = $this->paymentModel->findWithRelations($id);
        if (!$payment) {
            Session::flash('error', 'Payment not found');
            header('Location: /payments');
            exit;
        }
        
        $currentPage = 'payments';
        require_once __DIR__ . '/../views/payments/show.php';
    }

    public function create()
    {
        Auth::requireInvestorOrAdmin();
        
        $investors = $this->userModel->getUsersByRole('investor');
        $projects = $this->projectModel->getAll();
        
        $currentPage = 'payments';
        require_once __DIR__ . '/../views/payments/create.php';
    }

    public function store()
    {
        Auth::requireInvestorOrAdmin();
        
        $required = ['amount', 'payment_type', 'fund_purpose', 'payment_method', 'payment_date'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                Session::flash('error', 'Please fill in all required fields');
                header('Location: /payments/create');
                exit;
            }
        }

        // Validate amount
        if (!is_numeric($_POST['amount']) || $_POST['amount'] <= 0) {
            Session::flash('error', 'Please enter a valid amount');
            header('Location: /payments/create');
            exit;
        }

        $user = Auth::user();
        
        $data = [
            'project_id' => !empty($_POST['project_id']) ? $_POST['project_id'] : null,
            'investor_id' => !empty($_POST['investor_id']) ? $_POST['investor_id'] : $user['id'],
            'amount' => $_POST['amount'],
            'currency' => $_POST['currency'] ?? 'USD',
            'payment_type' => $_POST['payment_type'],
            'fund_purpose' => $_POST['fund_purpose'],
            'is_project_related' => isset($_POST['is_project_related']) ? 1 : 0,
            'payment_method' => $_POST['payment_method'],
            'status' => $_POST['status'] ?? 'pending',
            'payment_date' => $_POST['payment_date'],
            'reference_number' => $_POST['reference_number'] ?? '',
            'notes' => $_POST['notes'] ?? '',
        ];

        $paymentId = $this->paymentModel->create($data);
        
        if ($paymentId) {
            Session::flash('success', 'Payment created successfully');
            header('Location: /payments/' . $paymentId);
        } else {
            Session::flash('error', 'Error creating payment');
            header('Location: /payments/create');
        }
        exit;
    }

    public function edit()
    {
        Auth::requireInvestorOrAdmin();
        
        $path = $_SERVER['REQUEST_URI'];
        $id = (int) basename(dirname($path));
        
        if (!$id) {
            header('Location: /payments');
            exit;
        }
        
        $payment = $this->paymentModel->findWithRelations($id);
        if (!$payment) {
            Session::flash('error', 'Payment not found');
            header('Location: /payments');
            exit;
        }
        
        $investors = $this->userModel->getUsersByRole('investor');
        $projects = $this->projectModel->getAll();
        
        $currentPage = 'payments';
        require_once __DIR__ . '/../views/payments/edit.php';
    }

    public function update()
    {
        Auth::requireInvestorOrAdmin();
        
        $path = $_SERVER['REQUEST_URI'];
        $segments = explode('/', trim($path, '/'));
        $id = null;
        
        foreach ($segments as $i => $segment) {
            if ($segment === 'payments' && isset($segments[$i + 1]) && is_numeric($segments[$i + 1])) {
                $id = (int) $segments[$i + 1];
                break;
            }
        }
        
        if (!$id) {
            header('Location: /payments');
            exit;
        }
        
        $payment = $this->paymentModel->find($id);
        if (!$payment) {
            Session::flash('error', 'Payment not found');
            header('Location: /payments');
            exit;
        }

        $required = ['amount', 'payment_type', 'fund_purpose', 'payment_method', 'payment_date'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                Session::flash('error', 'Please fill in all required fields');
                header('Location: /payments/' . $id . '/edit');
                exit;
            }
        }

        // Validate amount
        if (!is_numeric($_POST['amount']) || $_POST['amount'] <= 0) {
            Session::flash('error', 'Please enter a valid amount');
            header('Location: /payments/' . $id . '/edit');
            exit;
        }

        $data = [
            'project_id' => !empty($_POST['project_id']) ? $_POST['project_id'] : null,
            'investor_id' => !empty($_POST['investor_id']) ? $_POST['investor_id'] : $payment['investor_id'],
            'amount' => $_POST['amount'],
            'currency' => $_POST['currency'] ?? 'USD',
            'payment_type' => $_POST['payment_type'],
            'fund_purpose' => $_POST['fund_purpose'],
            'is_project_related' => isset($_POST['is_project_related']) ? 1 : 0,
            'payment_method' => $_POST['payment_method'],
            'status' => $_POST['status'] ?? $payment['status'],
            'payment_date' => $_POST['payment_date'],
            'reference_number' => $_POST['reference_number'] ?? '',
            'notes' => $_POST['notes'] ?? '',
        ];

        if ($this->paymentModel->update($id, $data)) {
            Session::flash('success', 'Payment updated successfully');
            header('Location: /payments/' . $id);
        } else {
            Session::flash('error', 'Error updating payment');
            header('Location: /payments/' . $id . '/edit');
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
            if ($segment === 'payments' && isset($segments[$i + 1]) && is_numeric($segments[$i + 1])) {
                $id = (int) $segments[$i + 1];
                break;
            }
        }
        
        if (!$id) {
            header('Location: /payments');
            exit;
        }

        if ($this->paymentModel->updateStatus($id, 'completed')) {
            Session::flash('success', 'Payment approved and marked as completed');
        } else {
            Session::flash('error', 'Error approving payment');
        }

        header('Location: /payments/' . $id);
        exit;
    }

    public function cancel()
    {
        Auth::requireAdmin();
        
        $path = $_SERVER['REQUEST_URI'];
        $segments = explode('/', trim($path, '/'));
        $id = null;
        
        foreach ($segments as $i => $segment) {
            if ($segment === 'payments' && isset($segments[$i + 1]) && is_numeric($segments[$i + 1])) {
                $id = (int) $segments[$i + 1];
                break;
            }
        }
        
        if (!$id) {
            header('Location: /payments');
            exit;
        }

        if ($this->paymentModel->updateStatus($id, 'failed')) {
            Session::flash('success', 'Payment cancelled');
        } else {
            Session::flash('error', 'Error cancelling payment');
        }

        header('Location: /payments/' . $id);
        exit;
    }

    public function delete()
    {
        Auth::requireAdmin();
        
        $path = $_SERVER['REQUEST_URI'];
        $segments = explode('/', trim($path, '/'));
        $id = null;
        
        foreach ($segments as $i => $segment) {
            if ($segment === 'payments' && isset($segments[$i + 1]) && is_numeric($segments[$i + 1])) {
                $id = (int) $segments[$i + 1];
                break;
            }
        }
        
        if (!$id) {
            header('Location: /payments');
            exit;
        }

        if ($this->paymentModel->delete($id)) {
            Session::flash('success', 'Payment deleted successfully');
        } else {
            Session::flash('error', 'Error deleting payment');
        }

        header('Location: /payments');
        exit;
    }
}
