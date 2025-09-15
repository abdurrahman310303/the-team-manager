<?php
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/models/Project.php';

$project = new Project();

$testData = [
    'name' => 'Debug Test Project',
    'description' => 'Test project for debugging',
    'status' => 'planning',
    'budget' => 1000,
    'start_date' => '2024-01-01',
    'end_date' => '2024-12-31',
    'project_manager_id' => 1
];

echo "Testing project creation...\n";
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
?>
