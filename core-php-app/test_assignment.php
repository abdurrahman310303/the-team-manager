<?php
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/models/Project.php';
require_once __DIR__ . '/models/User.php';

$project = new Project();
$user = new User();

// First, let's see what users exist
echo "Available users:\n";
$users = $user->all();
foreach ($users as $u) {
    echo "- ID: {$u['id']}, Name: {$u['name']}, Role: {$u['role_name']}\n";
}

echo "\n";

// Test assignment with existing user IDs
$projectId = 27; // Use the project ID from your URL
$existingUserIds = array_column($users, 'id');
$testUserIds = array_slice($existingUserIds, 0, 2); // Take first 2 users

echo "Testing user assignment...\n";
echo "Project ID: $projectId\n";
echo "User IDs: " . implode(', ', $testUserIds) . "\n\n";

try {
    $result = $project->assignUsers($projectId, $testUserIds);
    echo "Assignment result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
    
    // Check what users are actually assigned
    $assignedUsers = $project->getProjectUsers($projectId);
    echo "Currently assigned users:\n";
    if (!empty($assignedUsers)) {
        foreach ($assignedUsers as $user) {
            echo "- User ID: {$user['user_id']}, Name: {$user['user_name']}\n";
        }
    } else {
        echo "- No users assigned\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
