<?php
$title = 'User Details - Team Manager';
$currentPage = 'users';

// Use admin layout for all users for consistent UI
ob_start();
?>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
            <h1 class="page-title">User Details</h1>
            <p class="page-subtitle">View user information and account details</p>
        </div>
        <div>
            <a href="/users" class="btn btn-secondary">
                <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"></path>
                </svg>
                Back to Users
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div style="display: flex; align-items: center; padding: 30px; border-bottom: 1px solid #e5e5e5;">
        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 24px; font-weight: 700; font-size: 32px;">
            <?= strtoupper(substr($targetUser['name'], 0, 1)) ?>
        </div>
        <div style="flex: 1;">
            <h2 style="margin: 0 0 8px 0; font-size: 28px; font-weight: 600; color: #1a1a1a;">
                <?= htmlspecialchars($targetUser['name']) ?>
            </h2>
            <p style="margin: 0 0 8px 0; color: #666666; font-size: 16px;">
                <?= htmlspecialchars($targetUser['email']) ?>
            </p>
            <span class="badge <?php
                switch($targetUser['role_name']) {
                    case 'admin': echo 'badge-approved'; break;
                    case 'developer': 
                    case 'bd': 
                    case 'investor': echo 'badge-pending'; break;
                    default: echo 'badge-pending';
                }
            ?>" style="font-size: 14px;">
                <?= htmlspecialchars($targetUser['role_display_name'] ?? ucfirst($targetUser['role_name'])) ?>
            </span>
        </div>
    </div>

    <div style="padding: 30px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
            <!-- Account Information -->
            <div>
                <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600; color: #1a1a1a;">Account Information</h3>
                
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 4px;">Full Name</label>
                    <p style="margin: 0; color: #666666; font-size: 16px;"><?= htmlspecialchars($targetUser['name']) ?></p>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 4px;">Email Address</label>
                    <p style="margin: 0; color: #666666; font-size: 16px;"><?= htmlspecialchars($targetUser['email']) ?></p>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 4px;">User Role</label>
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
                </div>
            </div>

            <!-- System Information -->
            <div>
                <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600; color: #1a1a1a;">System Information</h3>
                
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 4px;">User ID</label>
                    <p style="margin: 0; color: #666666; font-size: 16px; font-family: monospace;">#<?= $targetUser['id'] ?></p>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 4px;">Member Since</label>
                    <p style="margin: 0; color: #666666; font-size: 16px;"><?= date('F j, Y', strtotime($targetUser['created_at'])) ?></p>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 4px;">Last Updated</label>
                    <p style="margin: 0; color: #666666; font-size: 16px;"><?= date('F j, Y g:i A', strtotime($targetUser['updated_at'])) ?></p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 12px; justify-content: flex-end; padding-top: 30px; border-top: 1px solid #e5e5e5; margin-top: 30px;">
            <a href="/users" class="btn btn-secondary">Back to Users</a>
            <a href="/users/<?= $targetUser['id'] ?>/edit" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Edit User
            </a>
            <?php if ($targetUser['id'] != Session::get('user')['id']): ?>
            <form action="/users/<?= $targetUser['id'] ?>/delete" method="POST" style="display: inline;" 
                  onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                <button type="submit" class="btn btn-danger">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;">
                        <polyline points="3,6 5,6 21,6"/>
                        <path d="m19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"/>
                    </svg>
                    Delete User
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../admin_layout.php';
?>
