<?php
$title = 'Roles - Team Manager';
$currentPage = 'roles';
ob_start();
?>

<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Roles</h1>
    <p class="page-subtitle">Manage system roles and permissions</p>
</div>

<div style="margin-bottom: 30px;">
    <a href="/roles/create" class="btn btn-primary">+ Add Role</a>
</div>

<!-- Roles Grid -->
<div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
    <?php foreach ($roles as $role): ?>
        <div class="card">
            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: #f5f5f5; border: 1px solid #e5e5e5; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                    <span style="font-weight: 600; color: #1a1a1a;">
                        <?= strtoupper(substr($role['name'], 0, 1)) ?>
                    </span>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 16px; font-weight: 600; color: #1a1a1a; margin-bottom: 5px;">
                        <?= htmlspecialchars($role['display_name']) ?>
                    </h3>
                    <p style="font-size: 14px; color: #666666; margin: 0;">
                        <?= htmlspecialchars($role['description']) ?>
                    </p>
                </div>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 15px; border-top: 1px solid #f5f5f5;">
                <div class="action-buttons">
                    <a href="/roles/<?= $role['id'] ?>" class="btn btn-sm btn-secondary">View</a>
                    <a href="/roles/<?= $role['id'] ?>/edit" class="btn btn-sm">Edit</a>
                </div>
                <span style="font-size: 12px; color: #999999; font-family: monospace;">
                    <?= htmlspecialchars($role['name']) ?>
                </span>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>
