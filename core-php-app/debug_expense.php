<?php
require_once 'core/Database.php';
require_once 'config/database.php';
require_once 'core/Model.php';
require_once 'models/Expense.php';

try {
    $expense = new Expense();
    
    $testData = [
        'project_id' => 2,
        'added_by' => 1,
        'title' => 'Test Expense',
        'description' => 'Test Description', 
        'amount' => 10.00,
        'category' => 'other',
        'expense_date' => date('Y-m-d'),
        'status' => 'pending'
    ];
    
    echo "Test data:\n";
    print_r($testData);
    
    echo "\nAttempting to create expense...\n";
    $result = $expense->create($testData);
    
    echo "Success! Created expense with ID: " . $result . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>
