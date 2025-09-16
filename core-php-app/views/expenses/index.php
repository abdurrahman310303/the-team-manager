<?php
$title = 'Expenses - Team Manager';
ob_start();
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Expenses</h1>
        <p class="page-subtitle">Manage and track expense reports</p>
    </div>
    <div class="page-actions">
        <?php if (Auth::hasAnyRole(['admin', 'bd', 'developer'])): ?>
        <a href="/expenses/create" class="btn btn-primary">
            Add Expense
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Expense Summary -->
<?php if (!empty($expenses)): ?>
<div class="stats-grid">
    <?php
    $totalPending = 0;
    $totalApproved = 0;
    $totalRejected = 0;
    
    foreach ($expenses as $expense) {
        switch ($expense['status']) {
            case 'pending':
                $totalPending += $expense['amount'];
                break;
            case 'approved':
                $totalApproved += $expense['amount'];
                break;
            case 'rejected':
                $totalRejected += $expense['amount'];
                break;
        }
    }
    ?>
    
    <div class="stat-card">
        <span class="stat-number">$<?= number_format($totalPending, 2) ?></span>
        <div class="stat-label">Pending</div>
    </div>
    
    <div class="stat-card">
        <span class="stat-number">$<?= number_format($totalApproved, 2) ?></span>
        <div class="stat-label">Approved</div>
    </div>
    
    <div class="stat-card">
        <span class="stat-number">$<?= number_format($totalRejected, 2) ?></span>
        <div class="stat-label">Rejected</div>
    </div>
</div>
<?php endif; ?>

<div class="table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Date</th>
                <?php if (Auth::hasRole('admin')): ?>
                <th>Employee</th>
                <?php endif; ?>
                <th>Title</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Status</th>
                <th style="width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($expenses)): ?>
            <tr>
                <td colspan="<?= Auth::hasRole('admin') ? '7' : '6' ?>" style="text-align: center; padding: 40px;">
                    <div style="color: #666666;">No expenses found</div>
                </td>
            </tr>
            <?php else: ?>
            <?php foreach ($expenses as $expense): ?>
            <tr>
                <td>
                    <div class="date-cell">
                        <?= date('M j, Y', strtotime($expense['expense_date'])) ?>
                    </div>
                </td>
                <?php if (Auth::hasRole('admin')): ?>
                <td>
                    <div class="user-info">
                        <strong><?= htmlspecialchars($expense['user_name'] ?? 'Unknown') ?></strong>
                    </div>
                </td>
                <?php endif; ?>
                <td>
                    <div style="max-width: 300px;">
                        <?= htmlspecialchars($expense['title']) ?>
                    </div>
                </td>
                <td>
                    <span class="status-badge status-active">
                        <?= ucfirst(str_replace('_', ' ', $expense['category'])) ?>
                    </span>
                </td>
                <td>
                    <strong>$<?= number_format($expense['amount'], 2) ?></strong>
                </td>
                <td>
                    <span class="status-badge status-<?= $expense['status'] ?>">
                        <?= ucfirst($expense['status']) ?>
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="/expenses/<?= $expense['id'] ?>" class="btn btn-sm">View</a>
                        <?php 
                        $canEdit = Auth::hasRole('admin') || 
                            ($expense['added_by'] == Auth::user()['id'] && $expense['status'] === 'pending');
                        if ($canEdit): 
                        ?>
                        <a href="/expenses/<?= $expense['id'] ?>/edit" class="btn btn-sm">Edit</a>
                        <?php endif; ?>
                        <?php 
                        $canDelete = Auth::hasRole('admin') || $expense['added_by'] == Auth::user()['id'];
                        if ($canDelete): 
                        ?>
                        <button onclick="deleteExpense(<?= $expense['id'] ?>)" class="btn btn-sm btn-danger">Delete</button>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function deleteExpense(id) {
    if (confirm('Are you sure you want to delete this expense?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/expenses/${id}/delete`;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>