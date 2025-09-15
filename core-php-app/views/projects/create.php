<?php
$title = 'Create Project - Team Manager';
ob_start();
?>

<div class="page-header">
    <h1 class="page-title">Create New Project</h1>
    <p class="page-subtitle">Fill in the project details below to create a new project.</p>
</div>

<div class="form-container">
    <form action="/projects/store" method="POST">
        <div class="form-group">
            <label for="name" class="form-label">Project Name *</label>
            <input type="text" name="name" id="name" required class="form-input" placeholder="Enter project name">
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" rows="3" class="form-textarea" placeholder="Project description..."></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
            <div class="form-group">
                <label for="budget" class="form-label">Budget</label>
                <input type="number" name="budget" id="budget" step="0.01" min="0" class="form-input" placeholder="0.00">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-input">
            </div>

            <div class="form-group">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-input">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="project_manager_id" class="form-label">Project Manager *</label>
                <select name="project_manager_id" id="project_manager_id" required class="form-select">
                    <option value="">Select a project manager</option>
                    <?php if (isset($users) && !empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['role_display_name'] ?? ucfirst($user['role_name'])) ?>)</option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No users available</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="planning">Planning</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="on_hold">On Hold</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>
        
        <div style="display: flex; gap: 10px; justify-content: flex-end; padding-top: 20px; border-top: 1px solid #e5e5e5;">
            <a href="/projects" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Project</button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>
