<?php
/**
 * Test Login Script
 * This script tests the login functionality
 */

require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/models/User.php';

// Test credentials
$email = 'bd@teammanager.com';
$password = '123';

echo "Testing login for: $email\n";
echo "Password: $password\n\n";

try {
    $userModel = new User();
    
    // Find user by email
    $user = $userModel->findByEmail($email);
    
    if (!$user) {
        echo "❌ User not found with email: $email\n";
        exit(1);
    }
    
    echo "✓ User found:\n";
    echo "  ID: {$user['id']}\n";
    echo "  Name: {$user['name']}\n";
    echo "  Email: {$user['email']}\n";
    echo "  Role: {$user['role_name']} ({$user['role_display_name']})\n";
    echo "  Password Hash: " . substr($user['password'], 0, 20) . "...\n\n";
    
    // Test password verification
    if ($userModel->verifyPassword($password, $user['password'])) {
        echo "✅ Password verification SUCCESSFUL!\n";
        echo "The user can log in with these credentials.\n";
    } else {
        echo "❌ Password verification FAILED!\n";
        echo "The password does not match the stored hash.\n";
        
        // Show what the hash should be
        $correctHash = $userModel->hashPassword($password);
        echo "Expected hash for '$password': " . substr($correctHash, 0, 20) . "...\n";
        echo "Stored hash: " . substr($user['password'], 0, 20) . "...\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
