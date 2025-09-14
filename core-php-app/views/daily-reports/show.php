<?php
$title = 'Daily Report Details - Team Manager';
$currentPage = 'daily-reports';

// Check if user is admin and use admin layout
if (isset($user) && $user['role_name'] === 'admin') {
    ob_start();
?>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
            <h1 class="page-title">Daily Report Details</h1>
            <p class="page-subtitle"><?= date('M j, Y', strtotime($report['report_date'])) ?> - <?= htmlspecialchars($report['user_name'] ?? 'Unknown User') ?></p>
        </div>
        <div style="display: flex; gap: 12px;">
            <?php 
            $canEdit = (isset($user) && ($user['role_name'] === 'admin' || $report['user_id'] == $user['id']));
            if ($canEdit): 
            ?>
            <a href="/daily-reports/<?= $report['id'] ?>/edit" class="btn btn-sm btn-primary">
                <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Report
            </a>
            <button onclick="deleteReport(<?= $report['id'] ?>)" class="btn btn-sm btn-danger">
                <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Delete
            </button>
            <?php endif; ?>
            <a href="/daily-reports" class="btn btn-sm btn-secondary">← Back to Reports</a>
        </div>
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
                Daily Report - <?= date('M j, Y', strtotime($report['report_date'])) ?>
            </h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <?= htmlspecialchars($report['user_name'] ?? 'Unknown User') ?>
                </div>
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <?= $report['hours_worked'] ?> hours worked
                </div>
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                        <?php
                        switch($report['report_type']) {
                            case 'developer': echo 'bg-blue-100 text-blue-800'; break;
                            case 'bd': echo 'bg-green-100 text-green-800'; break;
                            case 'general': echo 'bg-gray-100 text-gray-800'; break;
                            default: echo 'bg-gray-100 text-gray-800'; break;
                        }
                        ?>">
                        <?= ucwords($report['report_type']) ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="mt-5 flex lg:mt-0 lg:ml-4">
            <?php 
            $canEdit = (isset($user) && ($user['role_name'] === 'admin' || $report['user_id'] == $user['id']));
            if ($canEdit): 
            ?>
            <span class="sm:ml-3">
                <a href="/daily-reports/<?= $report['id'] ?>/edit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Report
                </a>
            </span>
            <?php endif; ?>
            <span class="sm:ml-3">
                <a href="/daily-reports" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    ← Back to Reports
                </a>
            </span>
        </div>
    </div>

    <div class="mt-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Work Completed</h3>
                
                <div class="prose max-w-none">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-900 whitespace-pre-wrap"><?= htmlspecialchars($report['work_completed'] ?? 'No work details provided.') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($report['challenges_faced'])): ?>
    <div class="mt-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Challenges Faced</h3>
                
                <div class="prose max-w-none">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-sm text-gray-900 whitespace-pre-wrap"><?= htmlspecialchars($report['challenges_faced']) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($report['next_plans'])): ?>
    <div class="mt-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Next Plans</h3>
                
                <div class="prose max-w-none">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-gray-900 whitespace-pre-wrap"><?= htmlspecialchars($report['next_plans']) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($report['report_type'] === 'bd'): ?>
    <div class="mt-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Business Development Metrics</h3>
                
                <dl class="grid grid-cols-2 gap-x-4 gap-y-6 sm:grid-cols-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Leads Generated</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900">
                            <?= $report['leads_generated'] ?? 0 ?>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Proposals Submitted</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900">
                            <?= $report['proposals_submitted'] ?? 0 ?>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Projects Locked</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900">
                            <?= $report['projects_locked'] ?? 0 ?>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Revenue Generated</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900">
                            $<?= number_format($report['revenue_generated'] ?? 0, 2) ?>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($report['notes'])): ?>
    <div class="mt-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Additional Notes</h3>
                
                <div class="prose max-w-none">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-900 whitespace-pre-wrap"><?= htmlspecialchars($report['notes']) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="mt-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Report Details</h3>
                
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Report Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <?= date('l, F j, Y', strtotime($report['report_date'])) ?>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Hours Worked</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <?= $report['hours_worked'] ?> hours
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Report Type</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <?= ucwords($report['report_type']) ?>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Submitted</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <?= date('M j, Y g:i A', strtotime($report['created_at'])) ?>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>
