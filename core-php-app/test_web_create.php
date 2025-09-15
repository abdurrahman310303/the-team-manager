<?php
// Simple test to see what the create method actually returns in the web context
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/models/Project.php';

// Set headers for proper output
header('Content-Type: text/plain');

echo "Testing Project create method in web context...\n";

$project = new Project();
$testData = [
    'name' => 'Web Context Test Project',
    'description' => 'Test project for web debugging',
    'status' => 'planning',
    'budget' => 1000,
    'start_date' => '2024-01-01',
    'end_date' => '2024-12-31',
    'project_manager_id' => 1
];

try {
    $result = $project->create($testData);
    
    echo "Result type: " . gettype($result) . "\n";
    echo "Result value: ";
    var_dump($result);
    
    if (is_object($result)) {
        echo "Result class: " . get_class($result) . "\n";
    }
    
    // Clean up if it's actually an ID
    if (is_numeric($result)) {
        $project->delete($result);
        echo "Test project cleaned up\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
