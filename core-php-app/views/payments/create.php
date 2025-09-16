<?php
$title = 'Create Payment - Team Manager';
ob_start();
?>

<div class="page-header">
    <h1 class="page-title">Create Payment</h1>
    <p class="page-subtitle">Create a new payment record for an employee.</p>
</div>

<div class="form-container">
    <form action="/payments/store" method="POST" enctype="multipart/form-data">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="investor_id" class="form-label">Investor (Payer) *</label>
                <?php if (Auth::hasRole('investor')): 
                    $currentUser = Auth::user();
                ?>
                    <input type="hidden" name="investor_id" value="<?= $currentUser['id'] ?>">
                    <input type="text" value="<?= htmlspecialchars($currentUser['name']) ?>" class="form-input" readonly>
                <?php else: ?>
                    <select name="investor_id" id="investor_id" required class="form-select">
                        <option value="">Select investor</option>
                        <?php foreach ($investors as $investor): ?>
                            <option value="<?= $investor['id'] ?>"><?= htmlspecialchars($investor['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="recipient_id" class="form-label">Recipient (Payee) *</label>
                <select name="recipient_id" id="recipient_id" required class="form-select">
                    <option value="">Select recipient</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?= $employee['id'] ?>"><?= htmlspecialchars($employee['name']) ?> (<?= htmlspecialchars($employee['role_display_name'] ?? $employee['role_name']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="payment_type" class="form-label">Payment Type *</label>
                <select name="payment_type" id="payment_type" required class="form-select">
                    <option value="">Select type</option>
                    <option value="investment">Investment</option>
                    <option value="expense_reimbursement">Expense Reimbursement</option>
                    <option value="profit_share">Profit Share</option>
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
                <label for="payment_date" class="form-label">Payment Date *</label>
                <input type="date" name="payment_date" id="payment_date" value="<?= date('Y-m-d') ?>" required class="form-input">
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" rows="3" class="form-textarea" placeholder="Additional details about this payment..."></textarea>
        </div>

        <div class="form-group">
            <label for="receipt_image" class="form-label">Receipt/Proof of Payment</label>
            <input type="file" name="receipt_image" id="receipt_image" accept="image/*,.pdf" class="form-input">
            <p class="form-help">Upload an image or PDF file as proof of payment (optional)</p>
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
