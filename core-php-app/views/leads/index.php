<?php
$title = 'Leads - Team Manager';
ob_start();
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Leads</h1>
        <p class="page-subtitle">Manage your sales leads and prospects</p>
    </div>
    <div class="page-actions">
        <?php if (Auth::hasAnyRole(['admin', 'bd'])): ?>
        <a href="/leads/create" class="btn btn-primary">
            <span style="margin-right: 6px;">+</span>Add Lead
        </a>
        <?php endif; ?>
    </div>
</div>

<div class="table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Company</th>
                <th>Contact</th>
                <th>Status</th>
                <th>Source</th>
                <th>Potential Value</th>
                <th>Assigned To</th>
                <th style="width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($leads)): ?>
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px;">
                    <div style="color: #666666;">No leads found</div>
                </td>
            </tr>
            <?php else: ?>
            <?php foreach ($leads as $lead): ?>
            <tr>
                <td>
                    <div class="user-info">
                        <strong><?= htmlspecialchars($lead['company_name']) ?></strong>
                    </div>
                </td>
                <td>
                    <div class="user-info">
                        <strong><?= htmlspecialchars($lead['contact_person']) ?></strong>
                        <div style="color: #666666; font-size: 12px;"><?= htmlspecialchars($lead['email']) ?></div>
                    </div>
                </td>
                <td>
                    <span class="status-badge status-<?= $lead['status'] ?>">
                        <?= ucfirst($lead['status']) ?>
                    </span>
                </td>
                <td><?= htmlspecialchars($lead['source'] ?? '-') ?></td>
                <td>
                    <?= isset($lead['estimated_value']) && $lead['estimated_value'] ? '$' . number_format($lead['estimated_value'], 2) : '-' ?>
                </td>
                <td><?= htmlspecialchars($lead['assigned_name'] ?? 'Unassigned') ?></td>
                <td>
                    <div class="action-buttons">
                        <a href="/leads/<?= $lead['id'] ?>" class="btn btn-sm">View</a>
                        <?php if (Auth::hasAnyRole(['admin', 'bd'])): ?>
                        <a href="/leads/<?= $lead['id'] ?>/edit" class="btn btn-sm">Edit</a>
                        <?php if (Auth::hasRole('admin')): ?>
                        <button onclick="deleteLead(<?= $lead['id'] ?>)" class="btn btn-sm btn-danger">Delete</button>
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
function deleteLead(id) {
    if (confirm('Are you sure you want to delete this lead?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/leads/${id}/delete`;
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
