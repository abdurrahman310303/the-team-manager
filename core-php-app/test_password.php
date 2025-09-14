<?php
// Test password hash
$password = 'password';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Generated hash: " . $hash . "\n";
echo "Verification: " . (password_verify($password, $hash) ? "SUCCESS" : "FAILED") . "\n";

// Test the hash from database
$dbHash = '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxDeGKgdyJDkH4Ee2Y9KbdBnBM2';
echo "Database hash verification: " . (password_verify($password, $dbHash) ? "SUCCESS" : "FAILED") . "\n";
?>
