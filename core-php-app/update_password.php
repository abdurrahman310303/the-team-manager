<?php
require_once 'core/Database.php';
require_once 'models/User.php';

try {
    $userModel = new User();
    $newPassword = password_hash('password', PASSWORD_DEFAULT);
    
    $db = Database::getInstance();
    $sql = "UPDATE users SET password = ? WHERE email = ?";
    $db->query($sql, [$newPassword, 'admin@teammanager.com']);
    
    echo "Password updated successfully!\n";
    echo "New hash: " . $newPassword . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
