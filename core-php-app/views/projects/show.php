<?php
$title = 'Project Details - Team Manager';
ob_start();
?>

<div class="main-container">
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title"><?= htmlspecialchars($project['name']) ?></h1>
            <div class="header-meta">
                <span class="status-badge status-<?= $project['status'] ?>">
                    <?= ucwords(str_replace('_', ' ', $project['status'])) ?>
                </span>
                <?php if ($project['budget']): ?>
                <span class="budget-info">Budget: $<?= number_format($project['budget'], 2) ?></span>
                <?php endif; ?>
            </div>
        </div>
        <div class="header-actions">
            <a href="/projects" class="btn-secondary">← Back to Projects</a>
            <?php if (Auth::hasRole('admin') || Auth::user()['id'] == $project['project_manager_id']): ?>
            <a href="/projects/<?= $project['id'] ?>/edit" class="btn-primary">Edit Project</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="content-section">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Project Details</h2>
            </div>
            <div class="card-body">
                <div class="details-grid">
                    <div class="detail-item">
                        <label class="detail-label">Description</label>
                        <div class="detail-value">
                            <?= nl2br(htmlspecialchars($project['description'] ?? 'No description provided')) ?>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <label class="detail-label">Project Manager</label>
                        <div class="detail-value">
                            <?= htmlspecialchars($project['project_manager_name'] ?? 'Not assigned') ?>
                        </div>
                    </div>
                    
                    <?php if ($project['start_date']): ?>
                    <div class="detail-item">
                        <label class="detail-label">Start Date</label>
                        <div class="detail-value">
                            <?= date('M j, Y', strtotime($project['start_date'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($project['end_date']): ?>
                    <div class="detail-item">
                        <label class="detail-label">End Date</label>
                        <div class="detail-value">
                            <?= date('M j, Y', strtotime($project['end_date'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="detail-item">
                        <label class="detail-label">Created</label>
                        <div class="detail-value">
                            <?= date('M j, Y g:i A', strtotime($project['created_at'])) ?>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <label class="detail-label">Last Updated</label>
                        <div class="detail-value">
                            <?= date('M j, Y g:i A', strtotime($project['updated_at'] ?? $project['created_at'])) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($assignedUsers)): ?>
    <div class="content-section">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Assigned Team Members</h2>
            </div>
            <div class="card-body">
                <div class="team-members">
                    <?php foreach ($assignedUsers as $user): ?>
                    <div class="team-member">
                        <div class="member-info">
                            <div class="member-name"><?= htmlspecialchars($user['user_name']) ?></div>
                            <div class="member-email"><?= htmlspecialchars($user['user_email']) ?></div>
                        </div>
                        <div class="member-role">
                            <?= htmlspecialchars($user['user_role_name'] ?? 'Team Member') ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #000;
}

.header-content {
    flex: 1;
}

.page-title {
    font-size: 2rem;
    font-weight: bold;
    color: #000;
    margin: 0 0 0.5rem 0;
}

.header-meta {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-planning { background-color: #dbeafe; color: #1e40af; }
.status-in_progress { background-color: #fef3c7; color: #92400e; }
.status-completed { background-color: #d1fae5; color: #065f46; }
.status-on_hold { background-color: #f3f4f6; color: #374151; }
.status-cancelled { background-color: #fee2e2; color: #991b1b; }

.budget-info {
    font-weight: 600;
    color: #000;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.detail-item {
    border-bottom: 1px solid #e5e5e5;
    padding-bottom: 1rem;
}

.detail-label {
    display: block;
    font-weight: bold;
    color: #000;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    font-size: 0.875rem;
    letter-spacing: 0.05em;
}

.detail-value {
    color: #333;
    line-height: 1.5;
}

.team-members {
    display: grid;
    gap: 1rem;
}

.team-member {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border: 2px solid #000;
    background-color: #fff;
}

.member-info {
    flex: 1;
}

.member-name {
    font-weight: bold;
    color: #000;
    margin-bottom: 0.25rem;
}

.member-email {
    color: #666;
    font-size: 0.875rem;
}

.member-role {
    padding: 0.25rem 0.75rem;
    background-color: #f8f8f8;
    border: 1px solid #000;
    font-size: 0.875rem;
    font-weight: 600;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .header-actions {
        width: 100%;
        justify-content: flex-start;
    }
    
    .details-grid {
        grid-template-columns: 1fr;
    }
    
    .team-member {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}
</style>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>
