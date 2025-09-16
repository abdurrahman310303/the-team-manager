<?php
/**
 * Test script for payments functionality
 */

require_once 'config/database.php';
require_once 'core/Database.php';
require_once 'core/Model.php';
require_once 'models/Payment.php';
require_once 'models/User.php';

try {
    echo "Testing Payment Model...\n";
    
    $paymentModel = new Payment();
    $userModel = new User();
    
    // Test findWithRelations method
    echo "Testing findWithRelations method...\n";
    $payment = $paymentModel->findWithRelations(1);
    if ($payment) {
        echo "✓ findWithRelations works - Found payment: " . $payment['amount'] . "\n";
    } else {
        echo "ℹ No payment found with ID 1\n";
    }
    
    // Test getAllWithRelations method
    echo "Testing getAllWithRelations method...\n";
    $payments = $paymentModel->getAllWithRelations();
    echo "✓ getAllWithRelations works - Found " . count($payments) . " payments\n";
    
    // Test getNonInvestors method
    echo "Testing getNonInvestors method...\n";
    $nonInvestors = $userModel->getNonInvestors();
    echo "✓ getNonInvestors works - Found " . count($nonInvestors) . " non-investor users\n";
    
    echo "\n✅ All payment tests passed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
