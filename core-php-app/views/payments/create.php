<?php
$title = 'Create Payment - Team Manager';
ob_start();
?>

<div class="page-header">
    <h1 class="page-title">Create Payment</h1>
    <p class="page-subtitle">Create a new payment record for an employee.</p>
</div>

<div class="form-container">
    <form action="/payments/store" method="POST">
        <div class="form-group">
            <label for="user_id" class="form-label">Employee *</label>
            <select name="user_id" id="user_id" required class="form-select">
                <option value="">Select an employee</option>
                <?php foreach ($employees as $employee): ?>
                    <option value="<?= $employee['id'] ?>"><?= htmlspecialchars($employee['name']) ?> (<?= htmlspecialchars($employee['role_display_name'] ?? $employee['role_name']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="payment_type" class="form-label">Payment Type *</label>
                <select name="payment_type" id="payment_type" required class="form-select">
                    <option value="">Select type</option>
                    <option value="salary">Salary</option>
                    <option value="bonus">Bonus</option>
                    <option value="commission">Commission</option>
                    <option value="overtime">Overtime</option>
                    <option value="reimbursement">Reimbursement</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="amount" class="form-label">Amount *</label>
                <input type="number" name="amount" id="amount" step="0.01" min="0" required class="form-input" placeholder="0.00">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="payment_date" class="form-label">Payment Date</label>
                <input type="date" name="payment_date" id="payment_date" value="<?= date('Y-m-d') ?>" class="form-input">
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" rows="3" class="form-textarea" placeholder="Additional details about this payment..."></textarea>
        </div>
        
        <div style="display: flex; gap: 10px; justify-content: flex-end; padding-top: 20px; border-top: 1px solid #e5e5e5;">
            <a href="/payments" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Payment</button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>
