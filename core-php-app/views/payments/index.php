<?php
$title = 'Payments - Team Manager';
ob_start();
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Payments</h1>
        <p class="page-subtitle">Manage employee payments and compensation</p>
    </div>
    <div class="page-actions">
        <?php if (Auth::hasAnyRole(['admin', 'investor'])): ?>
        <a href="/payments/create" class="btn btn-primary">
            Add Payment
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Payment Summary -->
<?php if (!empty($payments)): ?>
<div class="stats-grid">
    <?php
    $totalPending = 0;
    $totalCompleted = 0;
    $totalCancelled = 0;
    $totalAll = 0;
    
    foreach ($payments as $payment) {
        $totalAll += $payment['amount'];
        switch ($payment['status']) {
            case 'pending':
                $totalPending += $payment['amount'];
                break;
            case 'completed':
                $totalCompleted += $payment['amount'];
                break;
            case 'cancelled':
                $totalCancelled += $payment['amount'];
                break;
        }
    }
    ?>
    
    <div class="stat-card">
        <span class="stat-number">$<?= number_format($totalAll, 2) ?></span>
        <div class="stat-label">Total Payments</div>
    </div>
    
    <div class="stat-card">
        <span class="stat-number">$<?= number_format($totalPending, 2) ?></span>
        <div class="stat-label">Pending</div>
    </div>
    
    <div class="stat-card">
        <span class="stat-number">$<?= number_format($totalCompleted, 2) ?></span>
        <div class="stat-label">Completed</div>
    </div>
    
    <div class="stat-card">
        <span class="stat-number">$<?= number_format($totalCancelled, 2) ?></span>
        <div class="stat-label">Cancelled</div>
    </div>
</div>
<?php endif; ?>

<div class="table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>From</th>
                <th>To</th>
                <th>Type</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Status</th>
                <th style="width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($payments)): ?>
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px;">
                    <div style="color: #666666;">No payments found</div>
                </td>
            </tr>
            <?php else: ?>
            <?php foreach ($payments as $payment): ?>
            <tr>
                <td>
                    <div class="date-cell">
                        <?= date('M j, Y', strtotime($payment['payment_date'])) ?>
                    </div>
                </td>
                <td>
                    <div class="user-info">
                        <strong><?= htmlspecialchars($payment['investor_name'] ?? 'Unknown') ?></strong>
                        <div style="color: #666666; font-size: 12px;">Investor</div>
                    </div>
                </td>
                <td>
                    <div class="user-info">
                        <strong><?= htmlspecialchars($payment['recipient_name'] ?? 'Unknown') ?></strong>
                        <div style="color: #666666; font-size: 12px;">Recipient</div>
                    </div>
                </td>
                <td>
                    <span class="status-badge status-active">
                        <?= ucfirst($payment['payment_type']) ?>
                    </span>
                </td>
                <td>
                    <div style="max-width: 200px;">
                        <?= htmlspecialchars($payment['description'] ?: 'No description') ?>
                    </div>
                </td>
                <td>
                    <strong>$<?= number_format($payment['amount'], 2) ?></strong>
                </td>
                <td>
                    <span class="status-badge status-<?= $payment['status'] ?>">
                        <?= ucfirst($payment['status']) ?>
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="/payments/<?= $payment['id'] ?>" class="btn btn-sm">View</a>
                        <?php if (Auth::hasAnyRole(['admin', 'investor'])): ?>
                        <a href="/payments/<?= $payment['id'] ?>/edit" class="btn btn-sm">Edit</a>
                        <?php if (Auth::hasRole('admin')): ?>
                        <button onclick="deletePayment(<?= $payment['id'] ?>)" class="btn btn-sm btn-danger">Delete</button>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if (Auth::hasRole('admin')): ?>
<script>
function deletePayment(id) {
    if (confirm('Are you sure you want to delete this payment?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/payments/${id}/delete`;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?php endif; ?>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>
