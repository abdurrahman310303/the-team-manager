<?php
/**
 * Create BD User Script
 * This script creates the BD user with properly hashed password
 */

require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/models/User.php';

try {
    $userModel = new User();
    
    // BD user details
    $email = 'bd@teammanager.com';
    $password = '123';
    $name = 'Business Development Manager';
    
    // Check if BD role exists, if not create it
    $bdRole = null;
    $roles = $userModel->getAllRoles();
    foreach ($roles as $role) {
        if ($role['name'] === 'bd') {
            $bdRole = $role;
            break;
        }
    }
    
    if (!$bdRole) {
        echo "Creating BD role...\n";
        $roleData = [
            'name' => 'bd',
            'display_name' => 'Business Development',
            'description' => 'Business Development team member with access to leads, reports, and expenses'
        ];
        $roleId = $userModel->createRole($roleData);
        if ($roleId) {
            $bdRole = $userModel->findRole($roleId);
            echo "BD role created successfully.\n";
        } else {
            throw new Exception("Failed to create BD role");
        }
    } else {
        echo "BD role already exists.\n";
    }
    
    // Check if user already exists
    $existingUser = $userModel->findByEmail($email);
    
    if ($existingUser) {
        echo "User with email $email already exists. Updating password...\n";
        
        // Update password
        $hashedPassword = $userModel->hashPassword($password);
        
        // Use direct SQL to update password and role
        $db = Database::getInstance();
        $sql = "UPDATE users SET password = ?, role_id = ? WHERE id = ?";
        $success = $db->query($sql, [$hashedPassword, $bdRole['id'], $existingUser['id']]);
        
        if ($success) {
            echo "Password updated successfully for $email\n";
        } else {
            throw new Exception("Failed to update user");
        }
    } else {
        echo "Creating new BD user...\n";
        
        // Create new user
        $userData = [
            'name' => $name,
            'email' => $email,
            'password' => $password, // This will be hashed by createUser method
            'role_id' => $bdRole['id']
        ];
        
        $userId = $userModel->createUser($userData);
        if ($userId) {
            echo "BD user created successfully with ID: $userId\n";
        } else {
            throw new Exception("Failed to create BD user");
        }
    }
    
    // Verify the login credentials
    echo "\nVerifying credentials...\n";
    $user = $userModel->findByEmail($email);
    if ($user && $userModel->verifyPassword($password, $user['password'])) {
        echo "✓ Credentials verified successfully!\n";
        echo "Email: $email\n";
        echo "Password: $password\n";
        echo "Role: {$user['role_name']}\n";
    } else {
        echo "✗ Credential verification failed!\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\nBD user setup completed!\n";
?>
