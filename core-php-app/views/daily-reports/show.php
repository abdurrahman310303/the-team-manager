<?php
$title = 'Daily Report Details - Team Manager';
$currentPage = 'daily-reports';
ob_start();
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Daily Report Details</h1>
        <p class="page-subtitle"><?= date('M j, Y', strtotime($report['report_date'])) ?> - <?= htmlspecialchars($report['user_name'] ?? 'Unknown User') ?></p>
    </div>
    <div class="page-actions">
        <?php 
        $canEdit = (isset($user) && ($user['role_name'] === 'admin' || $report['user_id'] == $user['id']));
        if ($canEdit): 
        ?>
        <a href="/daily-reports/<?= $report['id'] ?>/edit" class="btn btn-primary">
            <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit Report
        </a>
        <?php if (Auth::hasRole('admin')): ?>
        <button onclick="deleteReport(<?= $report['id'] ?>)" class="btn btn-danger">
            <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            Delete
        </button>
        <?php endif; ?>
        <?php endif; ?>
        <a href="/daily-reports" class="btn btn-secondary">← Back to Reports</a>
    </div>
</div>

<div class="card-grid">
    <div class="card">
        <div class="card-header">
            <h3>Report Overview</h3>
        </div>
        <div class="card-content">
            <div class="info-grid">
                <div class="info-item">
                    <label>Employee</label>
                    <span><?= htmlspecialchars($report['user_name'] ?? 'Unknown User') ?></span>
                </div>
                <div class="info-item">
                    <label>Hours Worked</label>
                    <span><?= $report['hours_worked'] ?> hours</span>
                </div>
                <div class="info-item">
                    <label>Report Type</label>
                    <span class="badge badge-pending"><?= ucwords($report['report_type']) ?></span>
                </div>
                <div class="info-item">
                    <label>Date Submitted</label>
                    <span><?= date('M j, Y g:i A', strtotime($report['created_at'])) ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Work Completed</h3>
        </div>
        <div class="card-content">
            <div style="background: #f8f9fa; padding: 16px; border-radius: 6px; border: 1px solid #2a2a2a;">
                <p style="margin: 0; white-space: pre-wrap; line-height: 1.6;"><?= htmlspecialchars($report['work_completed'] ?? 'No work details provided.') ?></p>
            </div>
        </div>
    </div>

    <?php if (!empty($report['challenges_faced'])): ?>
    <div class="card">
        <div class="card-header">
            <h3>Challenges Faced</h3>
        </div>
        <div class="card-content">
            <div style="background: #f8f9fa; padding: 16px; border-radius: 6px; border: 1px solid #2a2a2a;">
                <p style="margin: 0; white-space: pre-wrap; line-height: 1.6;"><?= htmlspecialchars($report['challenges_faced']) ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($report['next_plans'])): ?>
    <div class="card">
        <div class="card-header">
            <h3>Next Plans</h3>
        </div>
        <div class="card-content">
            <div style="background: #f8f9fa; padding: 16px; border-radius: 6px; border: 1px solid #2a2a2a;">
                <p style="margin: 0; white-space: pre-wrap; line-height: 1.6;"><?= htmlspecialchars($report['next_plans']) ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($report['report_type'] === 'bd'): ?>
    <div class="card">
        <div class="card-header">
            <h3>Business Development Metrics</h3>
        </div>
        <div class="card-content">
            <div class="info-grid">
                <div class="info-item">
                    <label>Leads Generated</label>
                    <span style="font-size: 20px; font-weight: bold; color: #059669;"><?= $report['leads_generated'] ?? 0 ?></span>
                </div>
                <div class="info-item">
                    <label>Proposals Submitted</label>
                    <span style="font-size: 20px; font-weight: bold; color: #0891b2;"><?= $report['proposals_submitted'] ?? 0 ?></span>
                </div>
                <div class="info-item">
                    <label>Projects Locked</label>
                    <span style="font-size: 20px; font-weight: bold; color: #7c3aed;"><?= $report['projects_locked'] ?? 0 ?></span>
                </div>
                <div class="info-item">
                    <label>Revenue Generated</label>
                    <span style="font-size: 20px; font-weight: bold; color: #dc2626;">$<?= number_format($report['revenue_generated'] ?? 0, 2) ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($report['notes'])): ?>
    <div class="card">
        <div class="card-header">
            <h3>Additional Notes</h3>
        </div>
        <div class="card-content">
            <div style="background: #f8f9fa; padding: 16px; border-radius: 6px; border: 1px solid #2a2a2a;">
                <p style="margin: 0; white-space: pre-wrap; line-height: 1.6;"><?= htmlspecialchars($report['notes']) ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function deleteReport(id) {
    if (confirm('Are you sure you want to delete this daily report? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/daily-reports/${id}/delete`;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
?>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>
