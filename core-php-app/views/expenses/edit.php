<?php
$title = 'Edit Expense - Team Manager';
$currentPage = 'expenses';
ob_start();
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Expense</h1>
        <p class="page-subtitle">Update expense details and information</p>
    </div>
    <div class="page-actions">
        <a href="/expenses" class="btn btn-secondary">← Back to Expenses</a>
    </div>
</div>

<div class="form-container">
    <form action="/expenses/<?= $expense['id'] ?>/update" method="POST" enctype="multipart/form-data">
        
        <!-- Basic Information Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Expense Information
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="expense_date" class="form-label">Expense Date *</label>
                    <input type="date" name="expense_date" id="expense_date" value="<?= $expense['expense_date'] ?>" required class="form-input">
                </div>
                <div class="form-group">
                    <label for="amount" class="form-label">Amount *</label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #666666; font-weight: 500;">$</span>
                        <input type="number" name="amount" id="amount" step="0.01" min="0" required class="form-input" style="padding-left: 24px;" value="<?= $expense['amount'] ?>" placeholder="0.00">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="title" class="form-label">Title *</label>
                <input type="text" name="title" id="title" required class="form-input" value="<?= htmlspecialchars($expense['title'] ?? '') ?>" placeholder="Brief title for this expense">
            </div>
            
            <div class="form-group">
                <label for="category" class="form-label">Category *</label>
                <select name="category" id="category" required class="form-input">
                    <option value="">Select category</option>
                    <option value="development" <?= $expense['category'] === 'development' ? 'selected' : '' ?>>Development</option>
                    <option value="marketing" <?= $expense['category'] === 'marketing' ? 'selected' : '' ?>>Marketing</option>
                    <option value="infrastructure" <?= $expense['category'] === 'infrastructure' ? 'selected' : '' ?>>Infrastructure</option>
                    <option value="tools" <?= $expense['category'] === 'tools' ? 'selected' : '' ?>>Tools</option>
                    <option value="travel" <?= $expense['category'] === 'travel' ? 'selected' : '' ?>>Travel</option>
                    <option value="other" <?= $expense['category'] === 'other' ? 'selected' : '' ?>>Other</option>
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
                <textarea name="description" id="description" rows="3" required class="form-textarea" placeholder="Brief description of the expense..."><?= htmlspecialchars($expense['description'] ?? '') ?></textarea>
            </div>
        </div>

        <?php if (Auth::hasRole('admin')): ?>
        <!-- Admin Status Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Status (Admin Only)
            </h3>
            
            <div class="form-group">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-input">
                    <option value="pending" <?= $expense['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="approved" <?= $expense['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="rejected" <?= $expense['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>
        </div>
        <?php endif; ?>

        <!-- Receipt Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Receipt
            </h3>
            
            <?php if (!empty($expense['receipt_image'])): ?>
            <div style="margin-bottom: 20px;">
                <label class="form-label">Current Receipt</label>
                <div style="text-align: center; padding: 20px; background: #f9f9f9; border-radius: 6px; border: 1px solid #e5e5e5;">
                    <?php 
                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $fileExtension = strtolower(pathinfo($expense['receipt_image'], PATHINFO_EXTENSION));
                    ?>
                    <?php if (in_array($fileExtension, $imageExtensions)): ?>
                        <img src="/<?= htmlspecialchars($expense['receipt_image']) ?>" 
                             alt="Current Receipt" 
                             style="max-width: 300px; height: auto; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <?php else: ?>
                        <div style="padding: 40px; background: #f0f0f0; border-radius: 6px;">
                            <p style="color: #666666; margin: 0;">📄 <?= htmlspecialchars(basename($expense['receipt_image'])) ?></p>
                            <a href="/<?= htmlspecialchars($expense['receipt_image']) ?>" target="_blank" class="btn btn-sm" style="margin-top: 10px;">View File</a>
                        </div>
                    <?php endif; ?>
                    <p style="color: #666666; font-size: 12px; margin-top: 10px;">Current receipt</p>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="receipt_image" class="form-label">
                    <?= !empty($expense['receipt_image']) ? 'Upload New Receipt (Optional)' : 'Receipt Image' ?>
                </label>
                <input type="file" name="receipt_image" id="receipt_image" accept="image/*,.pdf" class="form-input">
                <small style="color: #666666; font-size: 12px; display: block; margin-top: 5px;">
                    <?= !empty($expense['receipt_image']) ? 'Upload a new file to replace the current receipt' : 'Upload a receipt image or PDF' ?> (optional). Supported formats: JPG, PNG, GIF, PDF
                </small>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div style="display: flex; justify-content: flex-end; gap: 12px; padding-top: 20px; border-top: 1px solid #e5e5e5;">
            <a href="/expenses" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Expense</button>
        </div>
    </form>
</div>
?>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>
