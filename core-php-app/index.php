<?php
/**
 * Main Application Entry Point
 */

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoload core files
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Session.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/Auth.php';

// Load models
require_once __DIR__ . '/models/Model.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Project.php';
require_once __DIR__ . '/models/Lead.php';
require_once __DIR__ . '/models/DailyReport.php';
require_once __DIR__ . '/models/Expense.php';
require_once __DIR__ . '/models/Payment.php';

// Load controllers
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/ProjectController.php';
require_once __DIR__ . '/controllers/LeadController.php';
require_once __DIR__ . '/controllers/DailyReportController.php';
require_once __DIR__ . '/controllers/ExpenseController.php';
require_once __DIR__ . '/controllers/PaymentController.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/RoleController.php';

// Initialize session
Session::start();

// Handle PHP built-in server routing
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);

// For built-in server, handle static files
if ($path !== '/' && file_exists(__DIR__ . $path)) {
    return false; // Let server handle static files
}

// Initialize router
$router = new Router();

// Authentication routes
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

// Dashboard routes
$router->get('/', [DashboardController::class, 'index']);
$router->get('/dashboard', [DashboardController::class, 'index']);

// Project routes
$router->get('/projects', [ProjectController::class, 'index']);
$router->get('/projects/create', [ProjectController::class, 'create']);
$router->post('/projects/store', [ProjectController::class, 'store']);
$router->get('/projects/{id}', [ProjectController::class, 'show']);
$router->get('/projects/{id}/edit', [ProjectController::class, 'edit']);
$router->post('/projects/{id}/update', [ProjectController::class, 'update']);
$router->post('/projects/{id}/delete', [ProjectController::class, 'delete']);

// Lead routes
$router->get('/leads', [LeadController::class, 'index']);
$router->get('/leads/create', [LeadController::class, 'create']);
$router->post('/leads/store', [LeadController::class, 'store']);
$router->get('/leads/{id}', [LeadController::class, 'show']);
$router->get('/leads/{id}/edit', [LeadController::class, 'edit']);
$router->post('/leads/{id}/update', [LeadController::class, 'update']);
$router->post('/leads/{id}/delete', [LeadController::class, 'delete']);

// Daily Report routes
$router->get('/daily-reports', [DailyReportController::class, 'index']);
$router->get('/daily-reports/create', [DailyReportController::class, 'create']);
$router->post('/daily-reports/store', [DailyReportController::class, 'store']);
$router->get('/daily-reports/{id}', [DailyReportController::class, 'show']);
$router->get('/daily-reports/{id}/edit', [DailyReportController::class, 'edit']);
$router->post('/daily-reports/{id}/update', [DailyReportController::class, 'update']);
$router->post('/daily-reports/{id}/delete', [DailyReportController::class, 'delete']);

// Expense routes
$router->get('/expenses', [ExpenseController::class, 'index']);
$router->get('/expenses/create', [ExpenseController::class, 'create']);
$router->post('/expenses/store', [ExpenseController::class, 'store']);
$router->get('/expenses/{id}', [ExpenseController::class, 'show']);
$router->get('/expenses/{id}/edit', [ExpenseController::class, 'edit']);
$router->post('/expenses/{id}/update', [ExpenseController::class, 'update']);
$router->post('/expenses/{id}/approve', [ExpenseController::class, 'approve']);
$router->post('/expenses/{id}/reject', [ExpenseController::class, 'reject']);
$router->post('/expenses/{id}/delete', [ExpenseController::class, 'delete']);

// Payment routes
$router->get('/payments', [PaymentController::class, 'index']);
$router->get('/payments/create', [PaymentController::class, 'create']);
$router->post('/payments/store', [PaymentController::class, 'store']);
$router->get('/payments/{id}', [PaymentController::class, 'show']);
$router->get('/payments/{id}/edit', [PaymentController::class, 'edit']);
$router->post('/payments/{id}/update', [PaymentController::class, 'update']);
$router->post('/payments/{id}/approve', [PaymentController::class, 'approve']);
$router->post('/payments/{id}/cancel', [PaymentController::class, 'cancel']);
$router->post('/payments/{id}/delete', [PaymentController::class, 'delete']);

// User routes
$router->get('/users', [UserController::class, 'index']);
$router->get('/users/create', [UserController::class, 'create']);
$router->post('/users/store', [UserController::class, 'store']);
$router->get('/users/{id}', [UserController::class, 'show']);
$router->get('/users/{id}/edit', [UserController::class, 'edit']);
$router->post('/users/{id}/update', [UserController::class, 'update']);
$router->post('/users/{id}/delete', [UserController::class, 'delete']);

// Role routes
$router->get('/roles', [RoleController::class, 'index']);
$router->get('/roles/create', [RoleController::class, 'create']);
$router->post('/roles/store', [RoleController::class, 'store']);
$router->get('/roles/{id}', [RoleController::class, 'show']);
$router->get('/roles/{id}/edit', [RoleController::class, 'edit']);
$router->post('/roles/{id}/update', [RoleController::class, 'update']);
$router->post('/roles/{id}/delete', [RoleController::class, 'delete']);

// Resolve the current route
try {
    $router->resolve();
} catch (Exception $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}
