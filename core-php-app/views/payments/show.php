<?php
$title = 'Payment Details - Team Manager';
$currentPage = 'payments';

// Check if user is admin and use admin layout
if (isset($user) && $user['role_name'] === 'admin') {
    ob_start();
?>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
            <h1 class="page-title">Payment Details</h1>
            <p class="page-subtitle"><?= ucwords(str_replace('_', ' ', $payment['payment_type'])) ?> - $<?= number_format($payment['amount'], 2) ?></p>
        </div>
        <div style="display: flex; gap: 12px;">
            <?php if ($payment['status'] === 'pending'): ?>
                <form action="/payments/<?= $payment['id'] ?>/approve" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-sm btn-success">
                        <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Approve
                    </button>
                </form>
                <form action="/payments/<?= $payment['id'] ?>/cancel" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-sm btn-danger">
                        <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </button>
                </form>
            <?php endif; ?>
            
            <?php 
            $canEdit = ($user['role_name'] === 'admin' || 
                       ($payment['investor_id'] == $user['id'] && in_array($user['role_name'], ['admin', 'investor'])));
            if ($canEdit): 
            ?>
            <a href="/payments/<?= $payment['id'] ?>/edit" class="btn btn-sm btn-primary">
                <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <?php endif; ?>
            
            <?php if ($user['role_name'] === 'admin'): ?>
            <button onclick="deletePayment(<?= $payment['id'] ?>)" class="btn btn-sm btn-danger">
                <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Delete
            </button>
            <?php endif; ?>
            
            <a href="/payments" class="btn btn-sm btn-secondary">← Back to Payments</a>
        </div>
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
            <div style="background: #f8f9fa; padding: 16px; border-radius: 6px; border: 1px solid #2a2a2a;">
                <p style="margin: 0; white-space: pre-wrap; line-height: 1.6;"><?= htmlspecialchars($payment['description']) ?></p>
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
            <div style="background: #f8f9fa; padding: 16px; border-radius: 6px; border: 1px solid #2a2a2a;">
                <p style="margin: 0; white-space: pre-wrap; line-height: 1.6;"><?= htmlspecialchars($payment['notes']) ?></p>
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
    return;
}

// For non-admin users, use the original layout
ob_start();
?>

<div class="px-4 py-6 sm:px-0">
    <div class="lg:flex lg:items-center lg:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Payment Details
            </h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                        <?php
                        switch($payment['status']) {
                            case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                            case 'completed': echo 'bg-green-100 text-green-800'; break;
                            case 'failed': echo 'bg-red-100 text-red-800'; break;
                            default: echo 'bg-gray-100 text-gray-800'; break;
                        }
                        ?>">
                        <?= ucfirst($payment['status']) ?>
                    </span>
                </div>
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                    </svg>
                    Amount: <?= $payment['currency'] ?? 'USD' ?> <?= number_format($payment['amount'], 2) ?>
                </div>
            </div>
        </div>
        <div class="mt-5 flex lg:mt-0 lg:ml-4">
            <span class="sm:ml-3">
                <a href="/payments" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    ← Back to Payments
                </a>
            </span>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Payment Details -->
        <div class="lg:col-span-2">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Payment Information</h3>
                    
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Type</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <?= ucwords(str_replace('_', ' ', $payment['payment_type'])) ?>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Amount</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <?= $payment['currency'] ?? 'USD' ?> <?= number_format($payment['amount'], 2) ?>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <?= ucwords(str_replace('_', ' ', $payment['payment_method'])) ?>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <?= date('M j, Y', strtotime($payment['payment_date'])) ?>
                            </dd>
                        </div>
                        
                        <?php if (!empty($payment['investor_name'])): ?>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Investor/Payer</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <?= htmlspecialchars($payment['investor_name']) ?>
                            </dd>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($payment['recipient_name'])): ?>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Recipient</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <?= htmlspecialchars($payment['recipient_name']) ?>
                            </dd>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($payment['reference_number'])): ?>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Reference Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <?= htmlspecialchars($payment['reference_number']) ?>
                            </dd>
                        </div>
                        <?php endif; ?>
                    </dl>

                    <?php if (!empty($payment['description'])): ?>
                    <div class="mt-6">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">
                            <?= htmlspecialchars($payment['description']) ?>
                        </dd>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($payment['notes'])): ?>
                    <div class="mt-6">
                        <dt class="text-sm font-medium text-gray-500">Notes</dt>
                        <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">
                            <?= htmlspecialchars($payment['notes']) ?>
                        </dd>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Receipt Image -->
        <div class="lg:col-span-1">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Receipt</h3>
                    
                    <?php if (!empty($payment['receipt_image'])): ?>
                    <div class="text-center">
                        <img src="/uploads/receipts/<?= htmlspecialchars($payment['receipt_image']) ?>" 
                             alt="Receipt" 
                             class="max-w-full h-auto rounded-lg shadow-md cursor-pointer"
                             onclick="openImageModal(this.src)">
                        <p class="mt-2 text-sm text-gray-500">Click to view full size</p>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-6">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No receipt uploaded</h3>
                        <p class="mt-1 text-sm text-gray-500">No receipt image was provided for this payment.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Receipt Image</h3>
                <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="text-center">
                <img id="modalImage" src="" alt="Receipt" class="max-w-full h-auto rounded-lg shadow-md">
            </div>
        </div>
    </div>
</div>

<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
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
require_once __DIR__ . '/../layout.php';
?>
