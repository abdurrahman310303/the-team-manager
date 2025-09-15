<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing create vs update behavior...\n";

require_once 'core/Database.php';
require_once 'core/Model.php';
require_once 'models/Project.php';

try {
    $project = new Project();
    
    // Test data exactly like what would come from the form
    $testData = [
        'name' => 'Test Project Create vs Update',
        'description' => 'Testing the difference',
        'status' => 'planning',
        'budget' => 1500.00,
        'start_date' => '2025-09-15',
        'end_date' => '2025-10-15',
        'project_manager_id' => 1,
        'client_id' => null
    ];
    
    echo "1. Testing CREATE operation:\n";
    echo "Data to create:\n";
    print_r($testData);
    
    $projectId = $project->create($testData);
    echo "Created project ID: " . $projectId . " (type: " . gettype($projectId) . ")\n";
    echo "Is numeric: " . (is_numeric($projectId) ? 'YES' : 'NO') . "\n";
    echo "Is greater than 0: " . ($projectId > 0 ? 'YES' : 'NO') . "\n";
    
    // Test the same validation logic from the controller
    if (!is_numeric($projectId) || $projectId <= 0) {
        echo "CONTROLLER VALIDATION: FAILED - Invalid project ID\n";
    } else {
        echo "CONTROLLER VALIDATION: PASSED\n";
    }
    
    echo "\n2. Testing UPDATE operation on the same project:\n";
    $updateData = [
        'name' => 'Updated Test Project',
        'description' => 'Updated description',
        'status' => 'in_progress',
        'budget' => 2000.00,
        'start_date' => '2025-09-15',
        'end_date' => '2025-11-15',
        'project_manager_id' => 1,
        'client_id' => null
    ];
    
    echo "Data to update:\n";
    print_r($updateData);
    
    $updateResult = $project->update($projectId, $updateData);
    echo "Update result: " . var_export($updateResult, true) . "\n";
    
    // Verify the project exists after both operations
    $foundProject = $project->find($projectId);
    if ($foundProject) {
        echo "\n3. Project verification SUCCESSFUL:\n";
        echo "Project name: " . $foundProject['name'] . "\n";
        echo "Project status: " . $foundProject['status'] . "\n";
    } else {
        echo "\n3. Project verification FAILED - Project not found\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
