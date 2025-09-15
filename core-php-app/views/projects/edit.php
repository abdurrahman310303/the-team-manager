<?php
$title = 'Edit Project - Team Manager';
ob_start();
?>

<div class="main-container">
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">Edit Project</h1>
            <p class="page-description">Update project details and manage team assignments</p>
        </div>
    </div>

    <div class="form-container">
        <form action="/projects/<?= $project['id'] ?>/update" method="POST" class="project-form">
            
            <!-- Basic Information Section -->
            <div class="form-section">
                <h2 class="section-title">Basic Information</h2>
                
                <div class="form-group">
                    <label for="name" class="form-label">Project Name *</label>
                    <input type="text" name="name" id="name" required class="form-input" 
                           value="<?= htmlspecialchars($project['name']) ?>"
                           placeholder="Enter project name">
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Project Description</label>
                    <textarea name="description" id="description" class="form-textarea" rows="4"
                              placeholder="Describe the project goals, scope, and key deliverables..."><?= htmlspecialchars($project['description'] ?? '') ?></textarea>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="budget" class="form-label">Project Budget</label>
                        <div class="input-group">
                            <span class="input-prefix">$</span>
                            <input type="number" name="budget" id="budget" step="0.01" min="0" class="form-input" 
                                   value="<?= $project['budget'] ?? '' ?>"
                                   placeholder="0.00">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label">Project Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="planning" <?= $project['status'] === 'planning' ? 'selected' : '' ?>>Planning</option>
                            <option value="in_progress" <?= $project['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                            <option value="on_hold" <?= $project['status'] === 'on_hold' ? 'selected' : '' ?>>On Hold</option>
                            <option value="completed" <?= $project['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="cancelled" <?= $project['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Timeline Section -->
            <div class="form-section">
                <h2 class="section-title">Project Timeline</h2>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-input"
                               value="<?= $project['start_date'] ?? '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="end_date" class="form-label">Expected End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-input"
                               value="<?= $project['end_date'] ?? '' ?>">
                    </div>
                </div>
            </div>

            <!-- Management Section -->
            <div class="form-section">
                <h2 class="section-title">Project Management</h2>
                
                <div class="form-group">
                    <label for="project_manager_id" class="form-label">Project Manager *</label>
                    <select name="project_manager_id" id="project_manager_id" required class="form-select">
                        <option value="">Select project manager</option>
                        <?php if (isset($users) && !empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['id'] ?>" <?= $project['project_manager_id'] == $user['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($user['name']) ?> 
                                    (<?= htmlspecialchars($user['role_display_name'] ?? ucfirst($user['role_name'])) ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <!-- Team Assignment Section -->
            <div class="form-section">
                <h2 class="section-title">Team Assignment</h2>
                
                <div class="form-group">
                    <label class="form-label">Assign Team Members</label>
                    <div class="checkbox-container">
                        <?php if (isset($users) && !empty($users)): ?>
                            <?php 
                            // Get array of assigned user IDs for easy checking
                            $assignedUserIds = array_column($assignedUsers ?? [], 'user_id');
                            ?>
                            <?php foreach ($users as $teamUser): ?>
                                <div class="checkbox-item">
                                    <input type="checkbox" name="assigned_users[]" value="<?= $teamUser['id'] ?>" 
                                           id="user_<?= $teamUser['id'] ?>"
                                           <?= in_array($teamUser['id'], $assignedUserIds) ? 'checked' : '' ?>
                                           class="checkbox-input">
                                    <label for="user_<?= $teamUser['id'] ?>" class="checkbox-label">
                                        <div class="user-info">
                                            <div class="user-name"><?= htmlspecialchars($teamUser['name']) ?></div>
                                            <div class="user-role"><?= htmlspecialchars($teamUser['role_display_name'] ?? ucfirst($teamUser['role_name'])) ?></div>
                                        </div>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="no-data">No team members available for assignment</p>
                        <?php endif; ?>
                    </div>
                    <p class="form-help">Select team members who will work on this project.</p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="/projects/<?= $project['id'] ?>" class="btn btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    Update Project
                </button>
            </div>
        </form>
    </div>
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

.header-content h1.page-title {
    font-size: 2rem;
    font-weight: bold;
    color: #000;
    margin: 0 0 0.5rem 0;
}

.page-description {
    color: #666;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

.form-section {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e5e5e5;
}

.form-section:last-of-type {
    border-bottom: none;
}

.section-title {
    font-size: 1.25rem;
    font-weight: bold;
    color: #000;
    margin: 0 0 1.5rem 0;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #ccc;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.input-group {
    position: relative;
}

.input-prefix {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
    font-weight: 600;
    z-index: 1;
}

.input-group .form-input {
    padding-left: 2rem;
}

.checkbox-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1rem;
    max-height: 300px;
    overflow-y: auto;
    padding: 1rem;
    border: 1px solid #cccccc;
    background-color: #f9f9f9;
    border-radius: 4px;
}

.checkbox-item {
    display: flex;
    align-items: flex-start;
    background: #fff;
    border: 1px solid #ccc;
    padding: 0.75rem;
    transition: all 0.2s;
}

.checkbox-item:hover {
    border-color: #000;
    background-color: #f0f0f0;
}

.checkbox-input {
    margin-right: 0.75rem;
    margin-top: 0.25rem;
    flex-shrink: 0;
}

.checkbox-label {
    cursor: pointer;
    flex: 1;
}

.user-info {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-weight: 600;
    color: #000;
    margin-bottom: 0.25rem;
}

.user-role {
    font-size: 0.875rem;
    color: #666;
}

.form-help {
    margin-top: 0.5rem;
    font-size: 0.875rem;
    color: #666;
}

.no-data {
    text-align: center;
    color: #666;
    font-style: italic;
    grid-column: 1 / -1;
    padding: 2rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 2px solid #000;
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
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .checkbox-container {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column-reverse;
    }
    
    .form-actions .btn {
        width: 100%;
        text-align: center;
    }
}
</style>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>
