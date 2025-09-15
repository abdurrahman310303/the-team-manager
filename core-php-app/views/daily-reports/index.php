<?php
$title = 'Daily Reports - Team Manager';
ob_start();
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Daily Reports</h1>
        <p class="page-subtitle">Track daily work progress and activities</p>
    </div>
    <div class="page-actions">
        <?php if (Auth::hasAnyRole(['admin', 'bd', 'developer'])): ?>
        <a href="/daily-reports/create" class="btn btn-primary">
            Add Report
        </a>
        <?php endif; ?>
    </div>
</div>

<div class="table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Date</th>
                <?php if (Auth::hasRole('admin')): ?>
                <th>Employee</th>
                <?php endif; ?>
                <th>Work Done</th>
                <th>Hours</th>
                <th>Challenges</th>
                <th style="width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($reports)): ?>
            <tr>
                <td colspan="<?= Auth::hasRole('admin') ? '6' : '5' ?>" style="text-align: center; padding: 40px;">
                    <div style="color: #666666;">No daily reports found</div>
                </td>
            </tr>
            <?php else: ?>
            <?php foreach ($reports as $report): ?>
            <tr>
                <td>
                    <div class="date-cell">
                        <?= date('M j, Y', strtotime($report['report_date'])) ?>
                    </div>
                </td>
                <?php if (Auth::hasRole('admin')): ?>
                <td>
                    <div class="user-info">
                        <strong><?= htmlspecialchars($report['user_name'] ?? 'Unknown') ?></strong>
                    </div>
                </td>
                <?php endif; ?>
                <td>
                    <div style="max-width: 300px;">
                        <?= htmlspecialchars(substr($report['work_completed'] ?? '', 0, 100)) ?>
                        <?= strlen($report['work_completed'] ?? '') > 100 ? '...' : '' ?>
                    </div>
                </td>
                <td>
                    <span class="status-badge status-active"><?= $report['hours_worked'] ?> hrs</span>
                </td>
                <td>
                    <div style="max-width: 200px; color: #666666;">
                        <?php if (isset($report['challenges_faced']) && $report['challenges_faced']): ?>
                            <?= htmlspecialchars(substr($report['challenges_faced'], 0, 50)) ?>
                            <?= strlen($report['challenges_faced']) > 50 ? '...' : '' ?>
                        <?php else: ?>
                            None
                        <?php endif; ?>
                    </div>
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="/daily-reports/<?= $report['id'] ?>" class="btn btn-sm">View</a>
                        <?php 
                        $canEdit = Auth::hasRole('admin') || $report['user_id'] == Auth::user()['id'];
                        if ($canEdit): 
                        ?>
                        <a href="/daily-reports/<?= $report['id'] ?>/edit" class="btn btn-sm">Edit</a>
                        <button onclick="deleteReport(<?= $report['id'] ?>)" class="btn btn-sm btn-danger">Delete</button>
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
function deleteReport(id) {
    if (confirm('Are you sure you want to delete this daily report?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/daily-reports/${id}/delete`;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>
