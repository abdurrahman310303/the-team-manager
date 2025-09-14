<?php
$title = 'Projects - Team Manager';
$currentPage = 'projects';
ob_start();
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Projects</h1>
        <p class="page-subtitle">Manage your development projects</p>
    </div>
    <div class="page-actions">
        <?php if (Auth::hasRole('admin')): ?>
        <a href="/projects/create" class="btn btn-primary">+ Create Project</a>
        <?php endif; ?>
    </div>
</div>

<div class="table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Project</th>
                <th>Status</th>
                <th>Budget</th>
                <th>Manager</th>
                <th style="width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($projects ?? [])): ?>
            <tr>
                <td colspan="5" style="text-align: center; padding: 40px;">
                    <div style="color: #666666;">No projects found</div>
                </td>
            </tr>
            <?php else: ?>
            <?php foreach ($projects as $project): ?>
            <tr>
                <td>
                    <div class="user-info">
                        <strong><?= htmlspecialchars($project['name']) ?></strong>
                        <div style="color: #666666; font-size: 12px;">
                            <?= htmlspecialchars(substr($project['description'] ?? '', 0, 50)) ?>
                            <?= strlen($project['description'] ?? '') > 50 ? '...' : '' ?>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="status-badge status-<?= $project['status'] ?>">
                        <?= ucwords(str_replace('_', ' ', $project['status'])) ?>
                    </span>
                </td>
                <td>
                    <strong><?= $project['budget'] ? '$' . number_format($project['budget'], 2) : 'Not set' ?></strong>
                </td>
                <td><?= htmlspecialchars($project['project_manager_name'] ?? 'Not assigned') ?></td>
                <td>
                    <div class="action-buttons">
                        <a href="/projects/<?= $project['id'] ?>" class="btn btn-sm">View</a>
                        <?php if (Auth::hasRole('admin')): ?>
                        <a href="/projects/<?= $project['id'] ?>/edit" class="btn btn-sm">Edit</a>
                        <button onclick="deleteProject(<?= $project['id'] ?>)" class="btn btn-sm btn-danger">Delete</button>
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
function deleteProject(id) {
    if (confirm('Are you sure you want to delete this project?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/projects/${id}/delete`;
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
