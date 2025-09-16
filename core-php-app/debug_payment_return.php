<?php
/**
 * Debug script for payment creation return values
 */

require_once 'config/database.php';
require_once 'core/Database.php';
require_once 'core/Model.php';
require_once 'models/Payment.php';

try {
    echo "Testing Payment creation return values...\n";
    
    $paymentModel = new Payment();
    
    $testData = [
        'investor_id' => 1,
        'recipient_id' => 3,
        'amount' => 50.00,
        'currency' => 'USD',
        'payment_type' => 'investment',
        'fund_purpose' => 'other',
        'is_project_related' => 0,
        'payment_method' => 'bank_transfer',
        'status' => 'pending',
        'payment_date' => date('Y-m-d'),
        'description' => 'Debug test payment'
    ];
    
    echo "\nCalling create method...\n";
    $result = $paymentModel->create($testData);
    
    echo "Result: " . var_export($result, true) . "\n";
    echo "Type: " . gettype($result) . "\n";
    echo "is_numeric check: " . (is_numeric($result) ? 'TRUE' : 'FALSE') . "\n";
    echo "Boolean check: " . ($result ? 'TRUE' : 'FALSE') . "\n";
    echo "Combined check: " . (($result && is_numeric($result)) ? 'TRUE' : 'FALSE') . "\n";
    
    if (is_object($result)) {
        echo "Object class: " . get_class($result) . "\n";
    }
    
    echo "\n✅ Test completed!\n";
    
} catch (Exception $e) {
    echo "❌ Exception caught: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
