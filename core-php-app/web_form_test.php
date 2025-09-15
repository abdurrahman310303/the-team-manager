<?php
// Minimal test to replicate web form submission behavior
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing web form CREATE simulation...\n";

// Simulate the exact web environment
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [
    'name' => 'Web Form Test Project',
    'description' => 'Description from web form',
    'status' => 'planning',
    'budget' => '1000',
    'start_date' => '2025-09-15',
    'end_date' => '2025-10-15',
    'project_manager_id' => '1'
];

echo "Simulated POST data:\n";
print_r($_POST);

try {
    // Include files in exact order as web request
    require_once 'core/Database.php';
    require_once 'core/Model.php';
    require_once 'models/Project.php';
    
    // Process data exactly like the controller does
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
    
    echo "\nProcessed data (exactly like controller):\n";
    print_r($data);
    
    // Validation exactly like controller
    if (empty($data['name']) || empty($data['project_manager_id'])) {
        echo "VALIDATION FAILED: Name and Project Manager are required\n";
        exit;
    }
    
    echo "\nValidation PASSED\n";
    
    // Create project exactly like controller
    $projectModel = new Project();
    echo "Creating project...\n";
    
    $projectId = $projectModel->create($data);
    
    echo "Project created with ID: " . $projectId . " (type: " . gettype($projectId) . ")\n";
    
    // Controller validation exactly as written
    if (!is_numeric($projectId) || $projectId <= 0) {
        echo "CONTROLLER VALIDATION: FAILED - Invalid project ID returned\n";
        echo "is_numeric: " . (is_numeric($projectId) ? 'true' : 'false') . "\n";
        echo "projectId <= 0: " . ($projectId <= 0 ? 'true' : 'false') . "\n";
    } else {
        echo "CONTROLLER VALIDATION: PASSED\n";
    }
    
    // Final verification
    $createdProject = $projectModel->find($projectId);
    if ($createdProject) {
        echo "Project verification: SUCCESS\n";
        echo "Created project name: " . $createdProject['name'] . "\n";
    } else {
        echo "Project verification: FAILED\n";
    }
    
} catch (Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
}
