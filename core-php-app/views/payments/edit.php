<?php
$title = 'Edit Payment - Team Manager';
$currentPage = 'payments';

// Get user from session for admin layout detection
$user = Session::get('user');

// Get user from session for admin layout detection
$user = Session::get('user');

// Check if user is admin and use admin layout
if (isset($user) && $user['role_name'] === 'admin') {
    ob_start();
?>

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1 class="page-title">Edit Payment</h1>
            <a href="/payments/<?= $payment['id'] ?>" class="btn btn-sm btn-secondary">
                <svg style="width: 14px; height: 14px; margin-right: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"></path>
                </svg>
                Back to Payment
            </a>
        </div>
        <p class="page-subtitle">Update payment record details and information</p>

<div class="form-container">
    <form action="/payments/<?= $payment['id'] ?>/update" method="POST" enctype="multipart/form-data">
        <!-- Row 1: Amount and Currency -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label class="form-label">Amount *</label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #666; z-index: 1;">$</span>
                    <input type="number" name="amount" step="0.01" min="0" required
                        class="form-input" style="padding-left: 28px;"
                        placeholder="0.00" value="<?= htmlspecialchars($payment['amount']) ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Currency</label>
                <select name="currency" class="form-select">
                    <option value="USD" <?= $payment['currency'] === 'USD' ? 'selected' : '' ?>>USD - US Dollar</option>
                    <option value="EUR" <?= $payment['currency'] === 'EUR' ? 'selected' : '' ?>>EUR - Euro</option>
                    <option value="GBP" <?= $payment['currency'] === 'GBP' ? 'selected' : '' ?>>GBP - British Pound</option>
                    <option value="BDT" <?= $payment['currency'] === 'BDT' ? 'selected' : '' ?>>BDT - Bangladeshi Taka</option>
                    <option value="INR" <?= $payment['currency'] === 'INR' ? 'selected' : '' ?>>INR - Indian Rupee</option>
                </select>
            </div>
        </div>

        <!-- Row 2: Payment Type and Fund Purpose -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label class="form-label">Payment Type *</label>
                                                <select name="payment_type" required
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select payment type</option>
                                    <option value="investment" <?= $payment['payment_type'] === 'investment' ? 'selected' : '' ?>>Investment</option>
                                    <option value="expense_reimbursement" <?= $payment['payment_type'] === 'expense_reimbursement' ? 'selected' : '' ?>>Expense Reimbursement</option>
                                    <option value="profit_share" <?= $payment['payment_type'] === 'profit_share' ? 'selected' : '' ?>>Profit Share</option>
                                    <option value="reimbursement" <?= $payment['payment_type'] === 'reimbursement' ? 'selected' : '' ?>>Reimbursement</option>
                                    <option value="other" <?= $payment['payment_type'] === 'other' ? 'selected' : '' ?>>Other</option>
                                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Fund Purpose *</label>
                <select name="fund_purpose" required class="form-select">
                    <option value="">Select purpose</option>
                    <option value="salaries" <?= $payment['fund_purpose'] === 'salaries' ? 'selected' : '' ?>>Salaries</option>
                    <option value="upwork_connects" <?= $payment['fund_purpose'] === 'upwork_connects' ? 'selected' : '' ?>>Upwork Connects</option>
                    <option value="project_expenses" <?= $payment['fund_purpose'] === 'project_expenses' ? 'selected' : '' ?>>Project Expenses</option>
                    <option value="office_rent" <?= $payment['fund_purpose'] === 'office_rent' ? 'selected' : '' ?>>Office Rent</option>
                    <option value="equipment" <?= $payment['fund_purpose'] === 'equipment' ? 'selected' : '' ?>>Equipment</option>
                    <option value="marketing" <?= $payment['fund_purpose'] === 'marketing' ? 'selected' : '' ?>>Marketing</option>
                    <option value="other" <?= $payment['fund_purpose'] === 'other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
        </div>

        <!-- Row 3: Recipient and Related Project -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label class="form-label">Recipient</label>
                <select name="recipient_id" class="form-select">
                    <option value="">Select recipient</option>
                    <?php if (isset($users) && is_array($users)): ?>
                        <?php foreach ($users as $user_option): ?>
                            <option value="<?= $user_option['id'] ?>" <?= $payment['recipient_id'] == $user_option['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($user_option['name']) ?> (<?= htmlspecialchars($user_option['role_display_name'] ?? ucfirst($user_option['role_name'] ?? '')) ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Related Project</label>
                <select name="project_id" id="project_id" class="form-select">
                    <option value="">Not project-related</option>
                    <?php if (isset($projects) && is_array($projects)): ?>
                        <?php foreach ($projects as $project): ?>
                            <option value="<?= $project['id'] ?>" <?= $payment['project_id'] == $project['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($project['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <!-- Row 4: Project Related Checkbox -->
        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="checkbox" name="is_project_related" id="is_project_related" value="1"
                    style="width: 16px; height: 16px;" <?= $payment['is_project_related'] ? 'checked' : '' ?>>
                <span class="form-label" style="margin-bottom: 0;">This payment is project-related</span>
            </label>
        </div>

        <!-- Row 5: Payment Method and Payment Date -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label class="form-label">Payment Method *</label>
                <select name="payment_method" required class="form-select">
                    <option value="">Select method</option>
                    <option value="bank_transfer" <?= $payment['payment_method'] === 'bank_transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                    <option value="check" <?= $payment['payment_method'] === 'check' ? 'selected' : '' ?>>Check</option>
                    <option value="cash" <?= $payment['payment_method'] === 'cash' ? 'selected' : '' ?>>Cash</option>
                    <option value="digital_wallet" <?= $payment['payment_method'] === 'digital_wallet' ? 'selected' : '' ?>>Digital Wallet</option>
                    <option value="credit_card" <?= $payment['payment_method'] === 'credit_card' ? 'selected' : '' ?>>Credit Card</option>
                    <option value="other" <?= $payment['payment_method'] === 'other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Payment Date *</label>
                <input type="date" name="payment_date" required 
                    value="<?= date('Y-m-d', strtotime($payment['payment_date'])) ?>"
                    class="form-input">
            </div>
        </div>

        <!-- Row 6: Reference Number and Status -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label class="form-label">Reference Number</label>
                <input type="text" name="reference_number" class="form-input"
                    placeholder="Transaction ID, Check number, etc."
                    value="<?= htmlspecialchars($payment['reference_number'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="pending" <?= $payment['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="completed" <?= $payment['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="failed" <?= $payment['status'] === 'failed' ? 'selected' : '' ?>>Failed</option>
                    <option value="cancelled" <?= $payment['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>
        </div>

        <!-- Receipt Image Upload -->
        <div class="form-group">
            <label class="form-label">Receipt Image</label>
            <?php if (!empty($payment['receipt_image'])): ?>
                <div style="margin-bottom: 10px;">
                    <img src="/uploads/receipts/<?= htmlspecialchars($payment['receipt_image']) ?>" 
                         alt="Current Receipt" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                    <p style="font-size: 12px; color: #666; margin: 5px 0 0 0;">Current receipt image</p>
                </div>
            <?php endif; ?>
            <div style="border: 2px dashed #cccccc; border-radius: 4px; padding: 30px; text-align: center; background: #f9f9f9;">
                <svg style="margin: 0 auto 16px auto; width: 48px; height: 48px; color: #999;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <label for="receipt_image" class="btn btn-primary" style="cursor: pointer;">
                    <?= !empty($payment['receipt_image']) ? 'Replace Image' : 'Choose File' ?>
                    <input id="receipt_image" name="receipt_image" type="file" style="display: none;" accept="image/*">
                </label>
                <p style="margin: 12px 0 0 0; color: #666; font-size: 12px;">PNG, JPG, GIF up to 5MB</p>
            </div>
        </div>

        <!-- Description -->
        <div class="form-group">
            <label class="form-label">Description</label>
            <input type="text" name="description" class="form-input"
                placeholder="Brief description of payment"
                value="<?= htmlspecialchars($payment['description'] ?? '') ?>">
        </div>

        <!-- Notes -->
        <div class="form-group">
            <label class="form-label">Notes</label>
            <textarea name="notes" rows="3" class="form-textarea"
                placeholder="Additional notes about this payment..."><?= htmlspecialchars($payment['notes'] ?? '') ?></textarea>
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 12px; justify-content: flex-end; padding-top: 20px; border-top: 1px solid #e5e5e5;">
            <a href="/payments/<?= $payment['id'] ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;">
                    <path d="M5 12l5 5L20 7"/>
                </svg>
                Update Payment
            </button>
        </div>
    </form>
</div>

<?php
    $content = ob_get_clean();
    require_once __DIR__ . '/../admin_layout.php';
    return;
}

// For non-admin users, use the original layout
ob_start();
?>

<div class="px-4 py-6 sm:px-0">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Edit Payment</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Update payment transaction details and information.
                </p>
            </div>
        </div>
        
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form action="/payments/<?= $payment['id'] ?>/update" method="POST" enctype="multipart/form-data">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <!-- Basic Payment Information -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700">Amount *</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" name="amount" step="0.01" min="0" required
                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                                        placeholder="0.00" value="<?= htmlspecialchars($payment['amount']) ?>">
                                </div>
                            </div>

                            <div>
                                <label for="currency" class="block text-sm font-medium text-gray-700">Currency</label>
                                <select name="currency"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="USD" <?= $payment['currency'] === 'USD' ? 'selected' : '' ?>>USD - US Dollar</option>
                                    <option value="EUR" <?= $payment['currency'] === 'EUR' ? 'selected' : '' ?>>EUR - Euro</option>
                                    <option value="GBP" <?= $payment['currency'] === 'GBP' ? 'selected' : '' ?>>GBP - British Pound</option>
                                    <option value="BDT" <?= $payment['currency'] === 'BDT' ? 'selected' : '' ?>>BDT - Bangladeshi Taka</option>
                                    <option value="INR" <?= $payment['currency'] === 'INR' ? 'selected' : '' ?>>INR - Indian Rupee</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="payment_type" class="block text-sm font-medium text-gray-700">Payment Type *</label>
                                <select name="payment_type" required
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select payment type</option>
                                    <option value="investment" <?= $payment['payment_type'] === 'investment' ? 'selected' : '' ?>>Investment</option>
                                    <option value="expense_reimbursement" <?= $payment['payment_type'] === 'expense_reimbursement' ? 'selected' : '' ?>>Expense Reimbursement</option>
                                    <option value="profit_share" <?= $payment['payment_type'] === 'profit_share' ? 'selected' : '' ?>>Profit Share</option>
                                    <option value="salary" <?= $payment['payment_type'] === 'salary' ? 'selected' : '' ?>>Salary</option>
                                    <option value="bonus" <?= $payment['payment_type'] === 'bonus' ? 'selected' : '' ?>>Bonus</option>
                                    <option value="other" <?= $payment['payment_type'] === 'other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>

                            <div>
                                <label for="fund_purpose" class="block text-sm font-medium text-gray-700">Fund Purpose *</label>
                                <select name="fund_purpose" required
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select purpose</option>
                                    <option value="salaries" <?= $payment['fund_purpose'] === 'salaries' ? 'selected' : '' ?>>Salaries</option>
                                    <option value="upwork_connects" <?= $payment['fund_purpose'] === 'upwork_connects' ? 'selected' : '' ?>>Upwork Connects</option>
                                    <option value="project_expenses" <?= $payment['fund_purpose'] === 'project_expenses' ? 'selected' : '' ?>>Project Expenses</option>
                                    <option value="office_rent" <?= $payment['fund_purpose'] === 'office_rent' ? 'selected' : '' ?>>Office Rent</option>
                                    <option value="equipment" <?= $payment['fund_purpose'] === 'equipment' ? 'selected' : '' ?>>Equipment</option>
                                    <option value="marketing" <?= $payment['fund_purpose'] === 'marketing' ? 'selected' : '' ?>>Marketing</option>
                                    <option value="other" <?= $payment['fund_purpose'] === 'other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="recipient_id" class="block text-sm font-medium text-gray-700">Recipient</label>
                                <select name="recipient_id"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select recipient</option>
                                    <?php if (isset($users) && is_array($users)): ?>
                                        <?php foreach ($users as $user_option): ?>
                                            <option value="<?= $user_option['id'] ?>" <?= $payment['recipient_id'] == $user_option['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($user_option['name']) ?> (<?= htmlspecialchars($user_option['role_display_name'] ?? ucfirst($user_option['role_name'] ?? '')) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div>
                                <label for="project_id" class="block text-sm font-medium text-gray-700">Related Project</label>
                                <select name="project_id" id="project_id_non_admin"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Not project-related</option>
                                    <?php if (isset($projects) && is_array($projects)): ?>
                                        <?php foreach ($projects as $project): ?>
                                            <option value="<?= $project['id'] ?>" <?= $payment['project_id'] == $project['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($project['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="is_project_related" id="is_project_related_non_admin" value="1"
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                <?= $payment['is_project_related'] ? 'checked' : '' ?>>
                            <label for="is_project_related_non_admin" class="ml-2 block text-sm text-gray-900">
                                This payment is project-related
                            </label>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method *</label>
                                <select name="payment_method" required
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select method</option>
                                    <option value="bank_transfer" <?= $payment['payment_method'] === 'bank_transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                                    <option value="check" <?= $payment['payment_method'] === 'check' ? 'selected' : '' ?>>Check</option>
                                    <option value="cash" <?= $payment['payment_method'] === 'cash' ? 'selected' : '' ?>>Cash</option>
                                    <option value="digital_wallet" <?= $payment['payment_method'] === 'digital_wallet' ? 'selected' : '' ?>>Digital Wallet</option>
                                    <option value="credit_card" <?= $payment['payment_method'] === 'credit_card' ? 'selected' : '' ?>>Credit Card</option>
                                    <option value="other" <?= $payment['payment_method'] === 'other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>

                            <div>
                                <label for="payment_date" class="block text-sm font-medium text-gray-700">Payment Date *</label>
                                <input type="date" name="payment_date" required 
                                    value="<?= date('Y-m-d', strtotime($payment['payment_date'])) ?>"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <input type="text" name="description"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="Brief description of payment"
                                value="<?= htmlspecialchars($payment['description'] ?? '') ?>">
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" rows="3"
                                class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                                placeholder="Additional notes about this payment..."><?= htmlspecialchars($payment['notes'] ?? '') ?></textarea>
                        </div>
                    </div>
                    
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 space-x-3">
                        <a href="/payments/<?= $payment['id'] ?>" 
                            class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Payment
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-check project-related checkbox when project is selected (admin version)
if (document.getElementById('project_id')) {
    document.getElementById('project_id').addEventListener('change', function() {
        const checkbox = document.getElementById('is_project_related');
        if (checkbox) {
            checkbox.checked = this.value !== '';
        }
    });
}

// Auto-check project-related checkbox when project is selected (non-admin version)
if (document.getElementById('project_id_non_admin')) {
    document.getElementById('project_id_non_admin').addEventListener('change', function() {
        const checkbox = document.getElementById('is_project_related_non_admin');
        if (checkbox) {
            checkbox.checked = this.value !== '';
        }
    });
}

// File upload preview
if (document.getElementById('receipt_image')) {
    document.getElementById('receipt_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const fileName = file.name;
            const label = this.parentElement;
            if (label) {
                label.innerHTML = '<span>📎 ' + fileName + '</span>';
            }
        }
    });
}
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>
