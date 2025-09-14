<?php
$title = 'Dashboard - Team Manager';
$currentPage = 'dashboard';
ob_start();
?>

<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Welcome back, <?= htmlspecialchars($user['name']) ?>!</h1>
    <p class="page-subtitle">
        <?php
        switch($user['role_name']) {
            case 'admin':
                echo 'Manage your team and oversee all operations from here.';
                break;
            case 'developer':
                echo 'Track your development progress and manage your tasks.';
                break;
            case 'bd':
                echo 'Monitor your leads and business development activities.';
                break;
            case 'investor':
                echo 'Review project investments and financial performance.';
                break;
            default:
                echo 'Stay on top of your work and manage your daily activities.';
        }
        ?>
        <span class="badge badge-approved" style="margin-left: 10px;">
            <?= htmlspecialchars($user['role_display_name'] ?? ucfirst($user['role_name'])) ?>
        </span>
    </p>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <?php if ($user['role_name'] === 'admin'): ?>
        <div class="stat-card">
            <span class="stat-number"><?= $stats['total_projects'] ?></span>
            <div class="stat-label">Total Projects</div>
        </div>
        
        <div class="stat-card">
            <span class="stat-number"><?= $stats['total_users'] ?></span>
            <div class="stat-label">Total Users</div>
        </div>
        
        <div class="stat-card">
            <span class="stat-number"><?= $stats['pending_expenses'] ?></span>
            <div class="stat-label">Pending Expenses</div>
        </div>
        
        <div class="stat-card">
            <span class="stat-number">$<?= number_format($stats['total_payments'], 2) ?></span>
            <div class="stat-label">Total Payments</div>
        </div>
        
    <?php elseif ($user['role_name'] === 'developer'): ?>
        <div class="stat-card">
            <span class="stat-number"><?= $stats['total_projects'] ?></span>
            <div class="stat-label">Active Projects</div>
        </div>
        
        <div class="stat-card">
            <span class="stat-number"><?= $stats['my_reports_this_month'] ?></span>
            <div class="stat-label">Reports This Month</div>
        </div>
        
        <div class="stat-card">
            <span class="stat-number"><?= $stats['total_hours_this_month'] ?></span>
            <div class="stat-label">Hours This Month</div>
        </div>
        
        <div class="stat-card">
            <span class="stat-number"><?= $stats['pending_expenses'] ?></span>
            <div class="stat-label">Pending Expenses</div>
        </div>
        
    <?php elseif ($user['role_name'] === 'bd'): ?>
        <div class="stat-card">
            <span class="stat-number"><?= $stats['total_leads'] ?></span>
            <div class="stat-label">Total Leads</div>
        </div>
        
        <div class="stat-card">
            <span class="stat-number"><?= $stats['my_leads'] ?></span>
            <div class="stat-label">My Leads</div>
        </div>
        
        <div class="stat-card">
            <span class="stat-number"><?= $stats['qualified_leads'] ?></span>
            <div class="stat-label">Qualified Leads</div>
        </div>
        
        <div class="stat-card">
            <span class="stat-number"><?= $stats['won_leads'] ?></span>
            <div class="stat-label">Won Leads</div>
        </div>
        
    <?php elseif ($user['role_name'] === 'investor'): ?>
        <div class="stat-card">
            <span class="stat-number"><?= $stats['total_projects'] ?></span>
            <div class="stat-label">Total Projects</div>
        </div>
        
        <div class="stat-card">
            <span class="stat-number">$<?= number_format($stats['total_investment'], 2) ?></span>
            <div class="stat-label">Total Investment</div>
        </div>
        
        <div class="stat-card">
            <span class="stat-number">$<?= number_format($stats['completed_payments'], 2) ?></span>
            <div class="stat-label">Completed Payments</div>
        </div>
        
        <div class="stat-card">
            <span class="stat-number">$<?= number_format($stats['pending_payments'], 2) ?></span>
            <div class="stat-label">Pending Payments</div>
        </div>
        
    <?php else: ?>
        <div class="stat-card">
            <span class="stat-number"><?= $stats['my_reports_this_month'] ?></span>
            <div class="stat-label">Reports This Month</div>
        </div>
        
        <div class="stat-card">
            <span class="stat-number"><?= $stats['total_hours_this_month'] ?></span>
            <div class="stat-label">Hours This Month</div>
        </div>
        
        <div class="stat-card">
            <span class="stat-number"><?= $stats['pending_expenses'] ?></span>
            <div class="stat-label">Pending Expenses</div>
        </div>
    <?php endif; ?>
</div>

<!-- Role-specific Content Sections -->
<div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 30px;">
    <?php if ($user['role_name'] === 'admin' && !empty($dashboardData['recent_projects'])): ?>
    <div class="card">
        <h3 class="card-title">Recent Projects</h3>
        <div class="card-content">
            <?php foreach ($dashboardData['recent_projects'] as $project): ?>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f5f5f5;">
                <div>
                    <div style="font-weight: 500; color: #1a1a1a;"><?= htmlspecialchars($project['name']) ?></div>
                    <div style="font-size: 14px; color: #666666;"><?= htmlspecialchars($project['project_manager_name'] ?? 'No manager') ?></div>
                </div>
                <span class="badge <?php
                switch($project['status']) {
                    case 'planning': case 'in_progress': echo 'badge-pending'; break;
                    case 'completed': echo 'badge-approved'; break;
                    case 'on_hold': case 'cancelled': echo 'badge-rejected'; break;
                }
                ?>">
                    <?= ucwords(str_replace('_', ' ', $project['status'])) ?>
                </span>
            </div>
            <?php endforeach; ?>
            <div style="margin-top: 15px;">
                <a href="/projects" class="btn btn-sm">View all projects →</a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($dashboardData['my_recent_reports'])): ?>
    <div class="card">
        <h3 class="card-title">My Recent Reports</h3>
        <div class="card-content">
            <?php foreach ($dashboardData['my_recent_reports'] as $report): ?>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f5f5f5;">
                <div>
                    <div style="font-weight: 500; color: #1a1a1a;"><?= date('M j, Y', strtotime($report['report_date'])) ?></div>
                    <div style="font-size: 14px; color: #666666;"><?= $report['hours_worked'] ?> hours worked</div>
                </div>
                <a href="/daily-reports/<?= $report['id'] ?>" class="btn btn-sm">View</a>
            </div>
            <?php endforeach; ?>
            <div style="margin-top: 15px;">
                <a href="/daily-reports" class="btn btn-sm">View all reports →</a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($dashboardData['my_recent_leads'])): ?>
    <div class="card">
        <h3 class="card-title">My Recent Leads</h3>
        <div class="card-content">
            <?php foreach ($dashboardData['my_recent_leads'] as $lead): ?>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f5f5f5;">
                <div>
                    <div style="font-weight: 500; color: #1a1a1a;"><?= htmlspecialchars($lead['company_name']) ?></div>
                    <div style="font-size: 14px; color: #666666;"><?= htmlspecialchars($lead['contact_person']) ?></div>
                </div>
                <span class="badge <?php
                switch($lead['status']) {
                    case 'new': case 'contacted': echo 'badge-pending'; break;
                    case 'qualified': case 'proposal_sent': case 'closed_won': echo 'badge-approved'; break;
                    case 'closed_lost': echo 'badge-rejected'; break;
                    default: echo 'badge-pending';
                }
                ?>">
                    <?= ucfirst(str_replace('_', ' ', $lead['status'])) ?>
                </span>
            </div>
            <?php endforeach; ?>
            <div style="margin-top: 15px;">
                <a href="/leads" class="btn btn-sm">View all leads →</a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($dashboardData['recent_payments'])): ?>
    <div class="card">
        <h3 class="card-title">Recent Payments</h3>
        <div class="card-content">
            <?php foreach ($dashboardData['recent_payments'] as $payment): ?>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f5f5f5;">
                <div>
                    <div style="font-weight: 500; color: #1a1a1a;"><?= htmlspecialchars($payment['user_name'] ?? 'Unknown') ?></div>
                    <div style="font-size: 14px; color: #666666;">$<?= number_format($payment['amount'], 2) ?> - <?= ucfirst($payment['payment_type']) ?></div>
                </div>
                <span class="badge <?php
                switch($payment['status']) {
                    case 'pending': echo 'badge-pending'; break;
                    case 'completed': echo 'badge-approved'; break;
                    case 'failed': echo 'badge-rejected'; break;
                }
                ?>">
                    <?= ucfirst($payment['status']) ?>
                </span>
            </div>
            <?php endforeach; ?>
            <div style="margin-top: 15px;">
                <a href="/payments" class="btn btn-sm">View all payments →</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Quick Actions -->
<div style="margin-top: 30px;">
    <h3 class="form-section-header">Quick Actions</h3>
    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
        <?php if ($user['role_name'] === 'admin'): ?>
        <a href="/projects/create" class="card" style="text-decoration: none; display: block; padding: 16px; text-align: center;">
            <div style="font-weight: 500; color: #1a1a1a;">+ Create Project</div>
        </a>
        <a href="/users/create" class="card" style="text-decoration: none; display: block; padding: 16px; text-align: center;">
            <div style="font-weight: 500; color: #1a1a1a;">+ Add User</div>
        </a>
        <?php endif; ?>
        
        <?php if ($user['role_name'] === 'investor' || $user['role_name'] === 'admin'): ?>
        <a href="/payments/create" class="card" style="text-decoration: none; display: block; padding: 16px; text-align: center;">
            <div style="font-weight: 500; color: #1a1a1a;">+ Create Payment</div>
        </a>
        <?php endif; ?>
        
        <?php if ($user['role_name'] === 'bd' || $user['role_name'] === 'admin'): ?>
        <a href="/leads/create" class="card" style="text-decoration: none; display: block; padding: 16px; text-align: center;">
            <div style="font-weight: 500; color: #1a1a1a;">+ Add Lead</div>
        </a>
        <?php endif; ?>
        
        <?php if ($user['role_name'] !== 'investor'): ?>
        <a href="/daily-reports/create" class="card" style="text-decoration: none; display: block; padding: 16px; text-align: center;">
            <div style="font-weight: 500; color: #1a1a1a;">+ Add Report</div>
        </a>
        <a href="/expenses/create" class="card" style="text-decoration: none; display: block; padding: 16px; text-align: center;">
            <div style="font-weight: 500; color: #1a1a1a;">+ Submit Expense</div>
        </a>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>
