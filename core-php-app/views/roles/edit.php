<?php
$title = 'Edit Role - Team Manager';
$currentPage = 'roles';
ob_start();
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Role</h1>
        <p class="page-subtitle">Update role information and permissions</p>
    </div>
    <div class="page-actions">
        <a href="/roles" class="btn btn-secondary">← Back to Roles</a>
        <a href="/roles/<?= $role['id'] ?>" class="btn" style="margin-left: 10px; background: #1a1a1a; color: #ffffff; border: 1px solid #1a1a1a;">View Role</a>
    </div>
</div>

<div class="form-container">
    <form action="/roles/<?= $role['id'] ?>/update" method="POST">
        
        <!-- Basic Information Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="color: #1a1a1a; font-size: 16px; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e5e5e5;">
                Role Information
            </h3>
            
            <div class="form-group">
                <label for="name" class="form-label">Role Name *</label>
                <input type="text" id="name" name="name" class="form-input" value="<?= htmlspecialchars($role['name']) ?>" placeholder="e.g., admin, developer, bd" required>
                <small style="color: #666666; font-size: 12px;">Lowercase, no spaces. Used internally in the system.</small>
            </div>
            
            <div class="form-group">
                <label for="display_name" class="form-label">Display Name *</label>
                <input type="text" id="display_name" name="display_name" class="form-input" value="<?= htmlspecialchars($role['display_name']) ?>" placeholder="e.g., Administrator, Developer, Business Development" required>
                <small style="color: #666666; font-size: 12px;">Human-readable name shown to users.</small>
            </div>
            
            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" rows="4" class="form-textarea" placeholder="Describe the role's responsibilities and permissions..."><?= htmlspecialchars($role['description'] ?? '') ?></textarea>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e5e5;">
            <a href="/roles/<?= $role['id'] ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Role</button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>
