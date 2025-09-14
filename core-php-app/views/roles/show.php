<?php
$title = 'Role Details - Team Manager';
$currentPage = 'roles';
ob_start();
?>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
            <h1 class="page-title"><?= htmlspecialchars($role['display_name']) ?></h1>
            <p class="page-subtitle">Role: <?= htmlspecialchars($role['name']) ?></p>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="/roles/<?= $role['id'] ?>/edit" class="btn btn-primary">
                <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Role
            </a>
            <a href="/roles" class="btn btn-secondary">
                <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Roles
            </a>
        </div>
    </div>
</div>

<!-- Role Information -->
<div class="card">
    <div class="card-title">Role Information</div>
    <div class="card-content">
        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 20px; margin-bottom: 15px;">
            <strong>Role Name:</strong>
            <span><?= htmlspecialchars($role['name']) ?></span>
        </div>
        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 20px; margin-bottom: 15px;">
            <strong>Display Name:</strong>
            <span><?= htmlspecialchars($role['display_name']) ?></span>
        </div>
        <?php if (!empty($role['description'])): ?>
        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 20px; margin-bottom: 15px;">
            <strong>Description:</strong>
            <span><?= htmlspecialchars($role['description']) ?></span>
        </div>
        <?php endif; ?>
        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 20px; margin-bottom: 15px;">
            <strong>Created:</strong>
            <span><?= isset($role['created_at']) ? date('M j, Y \a\t g:i A', strtotime($role['created_at'])) : 'N/A' ?></span>
        </div>
        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 20px;">
            <strong>Users with this role:</strong>
            <span><?= count($usersWithRole) ?> user(s)</span>
        </div>
    </div>
</div>

<!-- Users with this Role -->
<?php if (!empty($usersWithRole)): ?>
<div class="card">
    <div class="card-title">Users with <?= htmlspecialchars($role['display_name']) ?> Role</div>
    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usersWithRole as $user): ?>
                <tr>
                    <td>
                        <div class="user-info">
                            <div style="font-weight: 500;"><?= htmlspecialchars($user['name']) ?></div>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <span class="badge <?= $user['is_active'] ? 'badge-approved' : 'badge-pending' ?>">
                            <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                    <td class="date-cell">
                        <?= date('M j, Y', strtotime($user['created_at'])) ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="/users/<?= $user['id'] ?>" class="btn btn-sm">View</a>
                            <a href="/users/<?= $user['id'] ?>/edit" class="btn btn-sm">Edit</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php else: ?>
<div class="card">
    <div class="card-title">Users with <?= htmlspecialchars($role['display_name']) ?> Role</div>
    <div class="card-content">
        <p style="color: #666666; text-align: center; padding: 20px;">No users currently have this role assigned.</p>
    </div>
</div>
<?php endif; ?>

<!-- Role Actions -->
<div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e5e5;">
    <?php if (empty($usersWithRole)): ?>
    <button onclick="deleteRole(<?= $role['id'] ?>)" class="btn btn-danger">
        <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
        </svg>
        Delete Role
    </button>
    <?php else: ?>
    <p style="color: #666666; font-size: 14px; margin: 0;">Cannot delete role with assigned users</p>
    <?php endif; ?>
</div>

<script>
function deleteRole(roleId) {
    if (confirm('Are you sure you want to delete this role? This action cannot be undone.')) {
        fetch(`/roles/${roleId}/delete`, {
            method: 'POST',
        }).then(response => {
            if (response.ok) {
                window.location.href = '/roles';
            } else {
                alert('Error deleting role');
            }
        }).catch(error => {
            alert('Error deleting role');
        });
    }
}
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>
