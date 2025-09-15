<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/web_debug.log');

echo "Testing web form project creation simulation...\n";

// Simulate web environment
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [
    'name' => 'Web Test Project',
    'description' => 'Test from web simulation',
    'status' => 'planning',
    'budget' => '2000.50',
    'start_date' => '2025-09-15',
    'end_date' => '2025-10-15',
    'project_manager_id' => '1',
    'assigned_users' => ['1']
];

echo "Simulated POST data:\n";
print_r($_POST);

// Include necessary files in the same order as web request
require_once 'core/Session.php';
require_once 'core/Database.php';
require_once 'core/Model.php';
require_once 'models/Project.php';
require_once 'models/User.php';
require_once 'core/Auth.php';

// Start session
Session::start();

try {
    echo "Creating ProjectController...\n";
    require_once 'controllers/ProjectController.php';
    
    $controller = new ProjectController();
    
    echo "Simulating store() method call...\n";
    
    // Prepare data exactly like the controller does
    $data = [
        'name' => $_POST['name'] ?? '',
        'description' => $_POST['description'] ?? '',
        'status' => $_POST['status'] ?? 'planning',
        'budget' => !empty($_POST['budget']) ? (float)$_POST['budget'] : null,
        'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
        'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
        'project_manager_id' => !empty($_POST['project_manager_id']) ? (int)$_POST['project_manager_id'] : null,
    ];
    
    echo "Processed data:\n";
    print_r($data);
    
    // Test the project creation directly
    $projectModel = new Project();
    $projectId = $projectModel->create($data);
    
    echo "Project created with ID: " . $projectId . " (type: " . gettype($projectId) . ")\n";
    
    // Test the validation logic from controller
    if (!is_numeric($projectId) || $projectId <= 0) {
        echo "CONTROLLER VALIDATION FAILED: Invalid project ID\n";
    } else {
        echo "CONTROLLER VALIDATION PASSED\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\nChecking web_debug.log for detailed logs...\n";
if (file_exists(__DIR__ . '/web_debug.log')) {
    echo "=== WEB DEBUG LOG CONTENT ===\n";
    echo file_get_contents(__DIR__ . '/web_debug.log');
}
