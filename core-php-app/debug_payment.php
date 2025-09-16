<?php
/**
 * Debug script for payment creation
 */

require_once 'config/database.php';
require_once 'core/Database.php';
require_once 'core/Model.php';
require_once 'models/Payment.php';

try {
    echo "Testing Payment creation...\n";
    
    $paymentModel = new Payment();
    
    $testData = [
        'project_id' => null,
        'investor_id' => 1,
        'recipient_id' => 3, // Using existing user ID
        'amount' => 100.00,
        'currency' => 'USD',
        'payment_type' => 'investment',
        'fund_purpose' => 'other',
        'is_project_related' => 0,
        'payment_method' => 'bank_transfer',
        'status' => 'pending',
        'payment_date' => date('Y-m-d'),
        'description' => 'Test payment'
    ];
    
    echo "Data to insert:\n";
    print_r($testData);
    
    echo "\nCalling create method...\n";
    $result = $paymentModel->create($testData);
    
    echo "Result type: " . gettype($result) . "\n";
    echo "Result value: " . var_export($result, true) . "\n";
    
    if (is_object($result)) {
        echo "Result is an object of class: " . get_class($result) . "\n";
    }
    
    echo "\n✅ Payment creation test completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
