<?php
$title = 'Create Expense - Team Manager';
$currentPage = 'expenses';
ob_start();
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Submit Expense</h1>
        <p class="page-subtitle">Submit an expense for approval and reimbursement</p>
    </div>
    <div class="page-actions">
        <a href="/expenses" class="btn btn-secondary">← Back to Expenses</a>
    </div>
</div>

<div class="form-container">
    <form action="/expenses/store" method="POST" enctype="multipart/form-data">
        <!-- Basic Information Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Expense Information
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="expense_date" class="form-label">Expense Date *</label>
                    <input type="date" name="expense_date" id="expense_date" value="<?= date('Y-m-d') ?>" required class="form-input">
                </div>
                <div class="form-group">
                    <label for="amount" class="form-label">Amount *</label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #666666; font-weight: 500;">$</span>
                        <input type="number" name="amount" id="amount" step="0.01" min="0" required class="form-input" style="padding-left: 24px;" placeholder="0.00">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="title" class="form-label">Title *</label>
                <input type="text" name="title" id="title" required class="form-input" placeholder="Brief title for this expense">
            </div>
            
            <div class="form-group">
                <label for="category" class="form-label">Category *</label>
                <select name="category" id="category" required class="form-input">
                    <option value="">Select category</option>
                    <option value="development">Development</option>
                    <option value="marketing">Marketing</option>
                    <option value="infrastructure">Infrastructure</option>
                    <option value="tools">Tools</option>
                    <option value="travel">Travel</option>
                    <option value="other">Other</option>
                </select>
            </div>
        </div>

        <!-- Description Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Description
            </h3>
            
            <div class="form-group">
                <label for="description" class="form-label">Description *</label>
                <textarea name="description" id="description" rows="3" required class="form-textarea" placeholder="Brief description of the expense..."></textarea>
            </div>
        </div>

        <!-- Receipt Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Receipt
            </h3>
            
            <div class="form-group">
                <label for="receipt_image" class="form-label">Receipt Image</label>
                <input type="file" name="receipt_image" id="receipt_image" accept="image/*,.pdf" class="form-input">
                <small style="color: #666666; font-size: 12px; display: block; margin-top: 5px;">
                    Upload a receipt image or PDF (optional). Supported formats: JPG, PNG, GIF, PDF
                </small>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div style="display: flex; justify-content: flex-end; gap: 12px; padding-top: 20px; border-top: 1px solid #e5e5e5;">
            <a href="/expenses" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Submit Expense</button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>
