<?php
$title = 'Create Expense - Team Manager';
ob_start();
?>

<div class="page-header">
    <h1 class="page-title">Submit Expense</h1>
    <p class="page-subtitle">Submit an expense for approval and reimbursement.</p>
</div>

<div class="form-container">
    <form action="/expenses/store" method="POST">
        <div class="form-group">
            <label for="date" class="form-label">Expense Date</label>
            <input type="date" name="date" id="date" value="<?= date('Y-m-d') ?>" class="form-input">
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description *</label>
            <input type="text" name="description" id="description" required class="form-input" placeholder="Brief description of the expense">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="amount" class="form-label">Amount *</label>
                <input type="number" name="amount" id="amount" step="0.01" min="0" required class="form-input" placeholder="0.00">
            </div>

            <div class="form-group">
                <label for="category" class="form-label">Category *</label>
                <select name="category" id="category" required class="form-select">
                    <option value="">Select category</option>
                    <option value="travel">Travel</option>
                    <option value="meals">Meals & Entertainment</option>
                    <option value="office_supplies">Office Supplies</option>
                    <option value="software">Software & Subscriptions</option>
                    <option value="training">Training & Education</option>
                    <option value="equipment">Equipment</option>
                    <option value="marketing">Marketing</option>
                    <option value="utilities">Utilities</option>
                    <option value="other">Other</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="receipt_url" class="form-label">Receipt URL</label>
            <input type="url" name="receipt_url" id="receipt_url" class="form-input" placeholder="https://drive.google.com/receipt-image">
            <small style="color: #666; display: block; margin-top: 5px;">Upload your receipt to Google Drive or similar service and paste the link here.</small>
        </div>
        
        <div style="display: flex; gap: 10px; justify-content: flex-end; padding-top: 20px; border-top: 1px solid #e5e5e5;">
            <a href="/expenses" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Submit Expense</button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>
