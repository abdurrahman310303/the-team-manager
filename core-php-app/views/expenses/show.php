<?php
$title = 'Expense Details - Team Manager';
$currentPage = 'expenses';
ob_start();
?>

<!-- Expense Header -->
<style>
.expense-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e5e5e5;
    flex-wrap: wrap;
    gap: 16px;
}

.expense-header-left {
    flex: 1;
    min-width: 0;
}

.expense-header-right {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .expense-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .expense-header-right {
        width: 100%;
        justify-content: flex-start;
    }
}
</style>

<div class="expense-header">
    <div class="expense-header-left">
        <h1 class="page-title">Expense Details</h1>
        <p class="page-subtitle"><?= htmlspecialchars($expense['title']) ?> - $<?= number_format($expense['amount'], 2) ?></p>
    </div>
    <div class="expense-header-right">
        <?php if (Auth::hasRole('admin') && $expense['status'] === 'pending'): ?>
            <form action="/expenses/<?= $expense['id'] ?>/approve" method="POST" style="display: inline;">
                <button type="submit" class="btn btn-sm btn-success">
                    <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Approve
                </button>
            </form>
            <form action="/expenses/<?= $expense['id'] ?>/reject" method="POST" style="display: inline;">
                <button type="submit" class="btn btn-sm btn-danger">
                    <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Reject
                </button>
            </form>
        <?php endif; ?>
        
        <?php 
        $canEdit = (Auth::hasRole('admin') || 
                   ($expense['added_by'] == Auth::user()['id'] && $expense['status'] === 'pending'));
        if ($canEdit): 
        ?>
        <a href="/expenses/<?= $expense['id'] ?>/edit" class="btn btn-sm btn-primary">
            <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit
        </a>
        <?php endif; ?>
        
        <?php 
        $canDelete = (Auth::hasRole('admin') || $expense['added_by'] == Auth::user()['id']);
        if ($canDelete): 
        ?>
        <button onclick="deleteExpense(<?= $expense['id'] ?>)" class="btn btn-sm btn-danger">
            <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            Delete
        </button>
        <?php endif; ?>
        
        <a href="/expenses" class="btn btn-sm btn-secondary">← Back to Expenses</a>
    </div>
</div>

<div class="card-grid">
    <div class="card">
        <div class="card-header">
            <h3>Expense Overview</h3>
        </div>
        <div class="card-content">
            <div class="info-grid">
                <div class="info-item">
                    <label>Status</label>
                    <span class="badge <?php
                        switch($expense['status']) {
                            case 'approved': echo 'badge-approved'; break;
                            case 'rejected': echo 'badge-rejected'; break;
                            default: echo 'badge-pending';
                        }
                    ?>">
                        <?= ucfirst($expense['status']) ?>
                    </span>
                </div>
                <div class="info-item">
                    <label>Amount</label>
                    <span style="font-size: 18px; font-weight: bold;">$<?= number_format($expense['amount'], 2) ?></span>
                </div>
                <div class="info-item">
                    <label>Category</label>
                    <span><?= ucwords(str_replace('_', ' ', $expense['category'])) ?></span>
                </div>
                <div class="info-item">
                    <label>Expense Date</label>
                    <span><?= date('M j, Y', strtotime($expense['expense_date'])) ?></span>
                </div>
                <div class="info-item">
                    <label>Submitted By</label>
                    <span><?= htmlspecialchars($expense['user_name']) ?></span>
                </div>
                <div class="info-item">
                    <label>Submitted</label>
                    <span><?= date('M j, Y g:i A', strtotime($expense['created_at'])) ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Description</h3>
        </div>
        <div class="card-content">
            <div style="background: #f8f9fa; padding: 16px; border-radius: 6px; border: 1px solid #2a2a2a;">
                <p style="margin: 0; white-space: pre-wrap; line-height: 1.6;"><?= htmlspecialchars($expense['description'] ?? 'No description provided.') ?></p>
            </div>
        </div>
    </div>

    <?php if (!empty($expense['receipt_image'])): ?>
    <div class="card">
        <div class="card-header">
            <h3>Receipt</h3>
        </div>
        <div class="card-content">
            <div style="text-align: center;">
                <img src="/<?= htmlspecialchars($expense['receipt_image']) ?>" 
                     alt="Receipt" 
                     style="max-width: 100%; height: auto; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); cursor: pointer;"
                     onclick="openImageModal(this.src)">
                <p style="margin-top: 12px; color: #666666; font-size: 12px;">Click to view full size</p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($expense['notes'])): ?>
    <div class="card">
        <div class="card-header">
            <h3>Additional Notes</h3>
        </div>
        <div class="card-content">
            <div style="background: #f8f9fa; padding: 16px; border-radius: 6px; border: 1px solid #2a2a2a;">
                <p style="margin: 0; white-space: pre-wrap; line-height: 1.6;"><?= htmlspecialchars($expense['notes']) ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Image Modal -->
<div id="imageModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000;">
    <div style="position: relative; top: 50%; left: 50%; transform: translate(-50%, -50%); max-width: 90%; max-height: 90%;">
        <button onclick="closeImageModal()" style="position: absolute; top: -40px; right: 0; background: none; border: none; color: white; font-size: 24px; cursor: pointer;">&times;</button>
        <img id="modalImage" src="" alt="Receipt" style="max-width: 100%; max-height: 100%; border-radius: 6px;">
    </div>
</div>

<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').style.display = 'block';
}

function closeImageModal() {
    document.getElementById('imageModal').style.display = 'none';
}

function deleteExpense(id) {
    if (confirm('Are you sure you want to delete this expense? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/expenses/${id}/delete`;
        document.body.appendChild(form);
        form.submit();
    }
}

// Close modal when clicking outside
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>