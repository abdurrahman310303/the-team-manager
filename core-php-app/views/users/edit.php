<?php
$title = 'Edit User - Team Manager';
$currentPage = 'users';

// Use admin layout for all users for consistent UI
ob_start();
?>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
            <h1 class="page-title">Edit User</h1>
            <p class="page-subtitle">Update user account information and role</p>
        </div>
        <div>
            <a href="/users/<?= $targetUser['id'] ?>" class="btn btn-secondary">
                <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"></path>
                </svg>
                Back to User
            </a>
        </div>
    </div>
</div>

<div class="form-container">
    <form action="/users/<?= $targetUser['id'] ?>/update" method="POST">
        <!-- User Avatar and Basic Info -->
        <div style="display: flex; align-items: center; padding: 20px; background: #f8f9fa; border-radius: 8px; margin-bottom: 30px;">
            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 20px; font-weight: 700; font-size: 24px;">
                <?= strtoupper(substr($targetUser['name'], 0, 1)) ?>
            </div>
            <div>
                <h3 style="margin: 0 0 4px 0; font-size: 18px; font-weight: 600;">
                    Editing: <?= htmlspecialchars($targetUser['name']) ?>
                </h3>
                <p style="margin: 0; color: #666; font-size: 14px;">
                    User ID: #<?= $targetUser['id'] ?> • Member since <?= date('M j, Y', strtotime($targetUser['created_at'])) ?>
                </p>
            </div>
        </div>

        <!-- Form Fields -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" required class="form-input"
                    placeholder="Enter full name"
                    value="<?= htmlspecialchars($targetUser['name']) ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" required class="form-input"
                    placeholder="user@example.com"
                    value="<?= htmlspecialchars($targetUser['email']) ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label class="form-label">User Role *</label>
                <select name="role_id" required class="form-select">
                    <option value="">Select role</option>
                    <?php if (isset($roles) && is_array($roles)): ?>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>" <?= $targetUser['role_id'] == $role['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($role['display_name'] ?? ucfirst($role['name'])) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-input"
                    placeholder="Leave blank to keep current password">
                <p style="font-size: 12px; color: #666; margin: 4px 0 0 0;">
                    Only enter a new password if you want to change it
                </p>
            </div>
        </div>

        <!-- Current Role Display -->
        <div class="form-group">
            <label class="form-label">Current Role</label>
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="badge <?php
                    switch($targetUser['role_name']) {
                        case 'admin': echo 'badge-approved'; break;
                        case 'developer': 
                        case 'bd': 
                        case 'investor': echo 'badge-pending'; break;
                        default: echo 'badge-pending';
                    }
                ?>">
                    <?= htmlspecialchars($targetUser['role_display_name'] ?? ucfirst($targetUser['role_name'])) ?>
                </span>
                <span style="color: #666; font-size: 14px;">
                    • Member since <?= date('F j, Y', strtotime($targetUser['created_at'])) ?>
                </span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 12px; justify-content: flex-end; padding-top: 20px; border-top: 1px solid #e5e5e5;">
            <a href="/users/<?= $targetUser['id'] ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;">
                    <path d="M5 12l5 5L20 7"/>
                </svg>
                Update User
            </button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>
