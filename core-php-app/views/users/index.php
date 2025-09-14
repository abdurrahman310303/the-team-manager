<?php
$title = 'Users - Team Manager';
$currentPage = 'users';
ob_start();
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Users</h1>
        <p class="page-subtitle">Manage system users and their roles</p>
    </div>
    <div class="page-actions">
        <?php if (Auth::hasRole('admin')): ?>
        <a href="/users/create" class="btn btn-primary">+ Add User</a>
        <?php endif; ?>
    </div>
</div>

<div class="table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Role</th>
                <th>Created</th>
                <th style="width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
            <tr>
                <td colspan="4" style="text-align: center; padding: 40px;">
                    <div style="color: #666666;">No users found</div>
                </td>
            </tr>
            <?php else: ?>
            <?php foreach ($users as $user): ?>
            <tr>
                <td>
                    <div class="user-info">
                        <strong><?= htmlspecialchars($user['name']) ?></strong>
                        <div style="color: #666666; font-size: 12px;"><?= htmlspecialchars($user['email']) ?></div>
                    </div>
                </td>
                <td>
                    <span class="status-badge status-active">
                        <?= htmlspecialchars($user['role_display_name'] ?? ucfirst($user['role_name'])) ?>
                    </span>
                </td>
                <td>
                    <div class="date-cell">
                        <?= date('M j, Y', strtotime($user['created_at'])) ?>
                    </div>
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="/users/<?= $user['id'] ?>" class="btn btn-sm">View</a>
                        <?php if (Auth::hasRole('admin') || Auth::user()['id'] == $user['id']): ?>
                        <a href="/users/<?= $user['id'] ?>/edit" class="btn btn-sm">Edit</a>
                        <?php endif; ?>
                        <?php if (Auth::hasRole('admin') && Auth::user()['id'] != $user['id']): ?>
                        <button onclick="deleteUser(<?= $user['id'] ?>)" class="btn btn-sm btn-danger">Delete</button>
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
function deleteUser(id) {
    if (confirm('Are you sure you want to delete this user?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/users/${id}/delete`;
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
