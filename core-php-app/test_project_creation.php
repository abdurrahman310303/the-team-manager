<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'core/Database.php';
require_once 'core/Model.php';
require_once 'models/Project.php';
require_once 'config/database.php';

echo "Testing project creation...\n";

try {
    $project = new Project();
    
    $testData = [
        'name' => 'Test Project ' . date('Y-m-d H:i:s'),
        'description' => 'Test description',
        'status' => 'planning',
        'budget' => 5000.00,
        'start_date' => date('Y-m-d'),
        'end_date' => date('Y-m-d', strtotime('+30 days')),
        'project_manager_id' => 1, // Assuming user ID 1 exists
        'client_id' => null
    ];
    
    echo "Data to insert:\n";
    var_dump($testData);
    
    echo "\nCreating project...\n";
    $projectId = $project->create($testData);
    
    echo "Project created with ID: " . $projectId . " (type: " . gettype($projectId) . ")\n";
    
    // Verify it was created
    $createdProject = $project->find($projectId);
    if ($createdProject) {
        echo "Verification: Project found in database\n";
        echo "Project name: " . $createdProject['name'] . "\n";
    } else {
        echo "ERROR: Project not found in database after creation!\n";
    }
    
    // Test user assignment
    echo "\nTesting user assignment...\n";
    $userIds = [1]; // Assuming user ID 1 exists
    $result = $project->assignUsers($projectId, $userIds);
    echo "Assignment result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
    
    // Check assignments
    $assignedUsers = $project->getProjectUsers($projectId);
    echo "Assigned users count: " . count($assignedUsers) . "\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
