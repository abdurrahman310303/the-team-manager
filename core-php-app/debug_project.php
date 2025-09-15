<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/debug.log');

echo "Testing project creation with debugging...\n";

require_once 'core/Database.php';
require_once 'core/Model.php';
require_once 'models/Project.php';

try {
    echo "1. Creating Project instance...\n";
    $project = new Project();
    
    $testData = [
        'name' => 'Debug Test Project',
        'description' => 'Test description',
        'status' => 'planning',
        'budget' => 1000.00,
        'start_date' => '2025-09-15',
        'end_date' => '2025-10-15',
        'project_manager_id' => 1,
        'client_id' => null
    ];
    
    echo "2. Test data prepared:\n";
    print_r($testData);
    
    echo "3. Testing fillable filtering...\n";
    $reflection = new ReflectionClass($project);
    $property = $reflection->getProperty('fillable');
    $property->setAccessible(true);
    $fillable = $property->getValue($project);
    echo "Fillable fields: " . implode(', ', $fillable) . "\n";
    
    // Test the filterFillable method
    $filteredData = array_intersect_key($testData, array_flip($fillable));
    echo "Filtered data:\n";
    print_r($filteredData);
    
    echo "4. Attempting to create project...\n";
    $projectId = $project->create($testData);
    
    echo "5. Project created successfully!\n";
    echo "Project ID: " . $projectId . " (type: " . gettype($projectId) . ")\n";
    
    // Verify the project exists
    $createdProject = $project->find($projectId);
    if ($createdProject) {
        echo "6. Project verification successful!\n";
        echo "Project name: " . $createdProject['name'] . "\n";
    } else {
        echo "6. ERROR: Project not found after creation!\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\nChecking debug.log for detailed logs...\n";
if (file_exists(__DIR__ . '/debug.log')) {
    echo "=== DEBUG LOG CONTENT ===\n";
    echo file_get_contents(__DIR__ . '/debug.log');
} else {
    echo "No debug.log file found\n";
}
