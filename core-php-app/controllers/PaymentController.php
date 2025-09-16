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
            // Investors see payments they've made
            $payments = $this->paymentModel->getPaymentsByInvestor($user['id']);
        } else {
            // Other roles should not see any payments
            $payments = [];
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
        
        $user = Auth::user();
        
        $currentPage = 'payments';
        require_once __DIR__ . '/../views/payments/show.php';
    }

    public function create()
    {
        Auth::requireInvestorOrAdmin();
        
        $employees = $this->userModel->getNonInvestors(); // Get all non-investor employees
        $investors = $this->userModel->getInvestors(); // Get all investors
        $projects = $this->projectModel->getAll();
        
        $currentPage = 'payments';
        require_once __DIR__ . '/../views/payments/create.php';
    }

    public function store()
    {
        $logFile = __DIR__ . '/../debug_payment.log';
        
        try {
            // Debug: Log all input data
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - PaymentController store() called
", FILE_APPEND);
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Input data: " . json_encode($_POST) . "
", FILE_APPEND);
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Files: " . json_encode($_FILES) . "
", FILE_APPEND);

            // Validate required fields
            $required = ['payment_type', 'amount', 'investor_id', 'recipient_id', 'payment_date'];
            $missingFields = array_diff($required, array_keys($_POST));
            
            if (!empty($missingFields)) {
                file_put_contents($logFile, date('Y-m-d H:i:s') . " - Missing fields: " . json_encode($missingFields) . "
", FILE_APPEND);
                Session::flash('error', 'Please fill all required fields: ' . implode(', ', $missingFields));
                return $this->create();
            }

            // Validate ENUM values for payment_type
            $validTypes = ['investment', 'expense_reimbursement', 'profit_share', 'reimbursement', 'other'];
            if (!in_array($_POST['payment_type'], $validTypes)) {
                file_put_contents($logFile, date('Y-m-d H:i:s') . " - Invalid payment_type: " . $_POST['payment_type'] . "
", FILE_APPEND);
                Session::flash('error', 'Invalid payment type selected');
                return $this->create();
            }

            $data = [
                'payment_type' => $_POST['payment_type'],
                'amount' => $_POST['amount'],
                'investor_id' => $_POST['investor_id'],
                'recipient_id' => $_POST['recipient_id'],
                'payment_date' => $_POST['payment_date'],
                'description' => $_POST['description'] ?? '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Handle file upload for receipt_image
            if (isset($_FILES['receipt_image']) && $_FILES['receipt_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/receipts/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $filename = time() . '_' . $_FILES['receipt_image']['name'];
                $uploadPath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['receipt_image']['tmp_name'], $uploadPath)) {
                    $data['receipt_image'] = $filename;
                    file_put_contents($logFile, date('Y-m-d H:i:s') . " - File uploaded successfully: " . $uploadPath . "\n", FILE_APPEND);
                } else {
                    file_put_contents($logFile, date('Y-m-d H:i:s') . " - File upload failed
", FILE_APPEND);
                }
            }

            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Data to be saved: " . json_encode($data) . "\n", FILE_APPEND);

            // Create the payment
            $result = $this->paymentModel->create($data);
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Payment creation result: " . var_export($result, true) . "\n", FILE_APPEND);
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Result type: " . gettype($result) . "\n", FILE_APPEND);
            
            // Check if payment was actually created by getting the last inserted payment
            $allPayments = $this->paymentModel->getAll();
            $lastPayment = !empty($allPayments) ? $allPayments[0] : null;
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Last payment in DB: " . json_encode($lastPayment) . "\n", FILE_APPEND);
            
            // Try to get payment by the expected ID if result is numeric
            if (is_numeric($result) && $result > 0) {
                $createdPayment = $this->paymentModel->find($result);
                file_put_contents($logFile, date('Y-m-d H:i:s') . " - Found payment by ID " . $result . ": " . json_encode($createdPayment) . "\n", FILE_APPEND);
                if ($createdPayment) {
                    Session::flash('success', 'Payment created successfully');
                    header('Location: /payments');
                    exit;
                }
            }
            
            // If we have a last payment and it matches our data, assume success
            if ($lastPayment && 
                $lastPayment['amount'] == $data['amount'] && 
                $lastPayment['payment_type'] == $data['payment_type'] &&
                $lastPayment['investor_id'] == $data['investor_id'] &&
                $lastPayment['recipient_id'] == $data['recipient_id']) {
                
                file_put_contents($logFile, date('Y-m-d H:i:s') . " - Payment appears to have been created successfully based on data match\n", FILE_APPEND);
                Session::flash('success', 'Payment created successfully');
                header('Location: /payments');
                exit;
            }
            
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Payment creation failed, result was: " . var_export($result, true) . "\n", FILE_APPEND);
            Session::flash('error', 'Error creating payment');
            return $this->create();
        } catch (Exception $e) {
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Exception in PaymentController::store: " . $e->getMessage() . "
", FILE_APPEND);
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Exception trace: " . $e->getTraceAsString() . "
", FILE_APPEND);
            Session::flash('error', 'Error creating payment: ' . $e->getMessage());
            return $this->create();
        }
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
        
        $investors = $this->userModel->getInvestors();
        $employees = $this->userModel->getNonInvestors(); // Get all non-investor employees
        $users = $this->userModel->getAll(); // Get all users for compatibility with edit form
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

        $required = ['amount', 'payment_type', 'payment_date'];
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
            'recipient_id' => !empty($_POST['user_id']) ? $_POST['user_id'] : $payment['recipient_id'],
            'amount' => $_POST['amount'],
            'currency' => $_POST['currency'] ?? 'USD',
            'payment_type' => $_POST['payment_type'],
            'fund_purpose' => $_POST['fund_purpose'] ?? 'other',
            'is_project_related' => isset($_POST['is_project_related']) ? 1 : 0,
            'payment_method' => $_POST['payment_method'] ?? 'bank_transfer',
            'status' => $_POST['status'] ?? $payment['status'],
            'payment_date' => $_POST['payment_date'],
            'reference_number' => $_POST['reference_number'] ?? '',
            'notes' => $_POST['notes'] ?? '',
            'description' => $_POST['description'] ?? '',
        ];

        // Handle file upload
        if (isset($_FILES['receipt_image']) && $_FILES['receipt_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/receipts/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileName = uniqid() . '_' . time() . '.' . pathinfo($_FILES['receipt_image']['name'], PATHINFO_EXTENSION);
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['receipt_image']['tmp_name'], $uploadPath)) {
                // Delete old file if it exists
                if (!empty($payment['receipt_image'])) {
                    $oldFile = $uploadDir . $payment['receipt_image'];
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
                $data['receipt_image'] = $fileName;
            }
        }

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
