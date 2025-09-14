<?php
$title = 'Edit Role - Team Manager';
$currentPage = 'roles';
ob_start();
?>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
            <h1 class="page-title">Edit Role</h1>
            <p class="page-subtitle">Update role information and permissions</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="/roles/<?= $role['id'] ?>" class="btn btn-secondary">
                <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                View Role
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
