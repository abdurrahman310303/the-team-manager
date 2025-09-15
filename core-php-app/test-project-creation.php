<?php
/**
 * Test Project Creation
 */

require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/models/Project.php';

echo "Testing project creation...\n\n";

try {
    $project = new Project();
    
    $testData = [
        'name' => 'Test Project',
        'description' => 'Test project for validation',
        'status' => 'planning',
        'budget' => 1000.00,
        'project_manager_id' => 1
    ];
    
    echo "Data to insert:\n";
    print_r($testData);
    
    $projectId = $project->create($testData);
    
    echo "✅ Project created successfully with ID: $projectId\n";
    
    // Clean up - delete the test project
    $project->delete($projectId);
    echo "✅ Test project cleaned up\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>
