<?php
$title = 'Payment Details - Team Manager';
$currentPage = 'payments';
$user = Auth::user();
ob_start();
?>

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1 class="page-title">Payment Details</h1>
        <p class="page-subtitle"><?= ucwords(str_replace('_', ' ', $payment['payment_type'])) ?> - $<?= number_format($payment['amount'], 2) ?></p>
    </div>
    <div style="display: flex; gap: 8px; align-items: center;">
        <?php if (Auth::hasRole('admin') && $payment['status'] === 'pending'): ?>
            <form action="/payments/<?= $payment['id'] ?>/approve" method="POST" style="display: inline;">
                <button type="submit" class="btn btn-sm btn-success">
                    <svg style="width: 14px; height: 14px; margin-right: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Approve
                </button>
            </form>
            <form action="/payments/<?= $payment['id'] ?>/cancel" method="POST" style="display: inline;">
                <button type="submit" class="btn btn-sm btn-danger">
                    <svg style="width: 14px; height: 14px; margin-right: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancel
                </button>
            </form>
        <?php endif; ?>
        
        <?php 
        $canEdit = (Auth::hasRole('admin') || 
                   ($payment['investor_id'] == $user['id'] && in_array($user['role_name'], ['admin', 'investor'])));
        if ($canEdit): 
        ?>
        <a href="/payments/<?= $payment['id'] ?>/edit" class="btn btn-sm btn-primary">
            <svg style="width: 14px; height: 14px; margin-right: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit
        </a>
        <?php endif; ?>
        
        <?php if (Auth::hasRole('admin')): ?>
        <button onclick="deletePayment(<?= $payment['id'] ?>)" class="btn btn-sm btn-danger">
            <svg style="width: 14px; height: 14px; margin-right: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            Delete
        </button>
        <?php endif; ?>
        
        <a href="/payments" class="btn btn-sm btn-secondary">
            <svg style="width: 14px; height: 14px; margin-right: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"></path>
            </svg>
            Back to Payments
        </a>
    </div>
</div>

<div class="card-grid">
    <div class="card">
        <div class="card-header">
            <h3>Payment Overview</h3>
        </div>
        <div class="card-content">
            <div class="info-grid">
                <div class="info-item">
                    <label>Status</label>
                    <span class="badge <?php
                        switch($payment['status']) {
                            case 'completed': echo 'badge-approved'; break;
                            case 'cancelled': case 'failed': echo 'badge-rejected'; break;
                            default: echo 'badge-pending';
                        }
                    ?>">
                        <?= ucfirst($payment['status']) ?>
                    </span>
                </div>
                <div class="info-item">
                    <label>Amount</label>
                    <span style="font-size: 18px; font-weight: bold;"><?= $payment['currency'] ?? 'USD' ?> <?= number_format($payment['amount'], 2) ?></span>
                </div>
                <div class="info-item">
                    <label>Payment Type</label>
                    <span><?= ucwords(str_replace('_', ' ', $payment['payment_type'])) ?></span>
                </div>
                <div class="info-item">
                    <label>Payment Date</label>
                    <span><?= date('M j, Y', strtotime($payment['payment_date'])) ?></span>
                </div>
                <div class="info-item">
                    <label>Payment Method</label>
                    <span><?= ucwords(str_replace('_', ' ', $payment['payment_method'])) ?></span>
                </div>
                <div class="info-item">
                    <label>Fund Purpose</label>
                    <span><?= ucwords(str_replace('_', ' ', $payment['fund_purpose'])) ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Parties Involved</h3>
        </div>
        <div class="card-content">
            <div class="info-grid">
                <div class="info-item">
                    <label>Investor/Payer</label>
                    <span><?= htmlspecialchars($payment['investor_name'] ?? 'Unknown') ?></span>
                </div>
                <?php if (!empty($payment['recipient_name'])): ?>
                <div class="info-item">
                    <label>Recipient</label>
                    <span><?= htmlspecialchars($payment['recipient_name']) ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($payment['project_name'])): ?>
                <div class="info-item">
                    <label>Related Project</label>
                    <span><?= htmlspecialchars($payment['project_name']) ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($payment['reference_number'])): ?>
                <div class="info-item">
                    <label>Reference Number</label>
                    <span><?= htmlspecialchars($payment['reference_number']) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (!empty($payment['description'])): ?>
    <div class="card">
        <div class="card-header">
            <h3>Description</h3>
        </div>
        <div class="card-content">
            <div style="background: #ffffff; padding: 16px; border-radius: 6px; border: 1px solid #e5e5e5;">
                <p style="margin: 0; white-space: pre-wrap; line-height: 1.6; color: #333333;"><?= htmlspecialchars($payment['description']) ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($payment['receipt_image'])): ?>
    <div class="card">
        <div class="card-header">
            <h3>Receipt</h3>
        </div>
        <div class="card-content">
            <div style="text-align: center;">
                <img src="/uploads/receipts/<?= htmlspecialchars($payment['receipt_image']) ?>" 
                     alt="Receipt" 
                     style="max-width: 100%; height: auto; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); cursor: pointer;"
                     onclick="openImageModal(this.src)">
                <p style="margin-top: 12px; color: #666666; font-size: 12px;">Click to view full size</p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($payment['notes'])): ?>
    <div class="card">
        <div class="card-header">
            <h3>Additional Notes</h3>
        </div>
        <div class="card-content">
            <div style="background: #ffffff; padding: 16px; border-radius: 6px; border: 1px solid #e5e5e5;">
                <p style="margin: 0; white-space: pre-wrap; line-height: 1.6; color: #333333;"><?= htmlspecialchars($payment['notes']) ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h3>Payment Timeline</h3>
        </div>
        <div class="card-content">
            <div class="info-grid">
                <div class="info-item">
                    <label>Created</label>
                    <span><?= date('M j, Y g:i A', strtotime($payment['created_at'])) ?></span>
                </div>
                <?php if ($payment['updated_at'] && $payment['updated_at'] != $payment['created_at']): ?>
                <div class="info-item">
                    <label>Last Updated</label>
                    <span><?= date('M j, Y g:i A', strtotime($payment['updated_at'])) ?></span>
                </div>
                <?php endif; ?>
                <div class="info-item">
                    <label>Project Related</label>
                    <span><?= $payment['is_project_related'] ? 'Yes' : 'No' ?></span>
                </div>
            </div>
        </div>
    </div>
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

function deletePayment(id) {
    if (confirm('Are you sure you want to delete this payment? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/payments/${id}/delete`;
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
